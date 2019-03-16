<?php

namespace App;

use App\Exceptions\MyExceptions\NotFoundReportByIdException;
use App\Exceptions\MyExceptions\NotFoundReportsException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\DB;
use ReCaptcha\ReCaptcha;

class Report extends Model
{
    /**
     * @return Report[]
     * @throws NotFoundReportsException
     */
    public static function findAll()
    {
        $response = self::orderBy('report_id', 'desc')->get();

        if ($response->count() === 0) {
            throw new NotFoundReportsException('Ничего не найдено');
        } else {
            foreach ($response as $item) {
                $item->updatedAt = self::formatDate($item->updatedAt);
                $item->createdAt = self::formatDate($item->createdAt);
                $item->tags = self::getTags($item->report_id);
                $user = User::getUserById($item->user_id);
                $item->userName = $user->name;
                $item->email = $user->email;
                $item->url = $user->url;
            }
            return $response;
        }
    }

    /**
     * @param int
     * @return Report|bool
     * @throws \Exception
     */
    public static function getById($id)
    {
        $response = self::find($id);
        if ($response === null) {
            return false;
        } else {
            $response->updatedAt = self::formatDate($response->updatedAt);
            $response->createdAt = self::formatDate($response->createdAt);
            $user = User::getUserById($response->user_id);
            $response->userName = $user->name;
            $response->email = $user->email;
            $response->url = $user->url;
            $response->tags = self::getTags($id);
            return $response;
        }
    }

    /**
     * @param int
     * @return Report
     * @throws NotFoundReportByIdException
     */
    public static function findById($id)
    {
        $response = self::find($id);

        if ($response === null) {
            throw new NotFoundReportByIdException("Ничего не найдено с id = {$id}");
        } else {
            $response->updatedAt = self::formatDate($response->updatedAt);
            $response->createdAt = self::formatDate($response->createdAt);
            $user = User::getUserById($response->user_id);
            $response->userName = $user->name;
            $response->email = $user->email;
            $response->url = $user->url;
            $response->tags = self::getTags($id);
            return $response;
        }
    }

    /**
     * @param int
     * @return Tag[]
     */
    public static function getTags($id) {
        $sql = "SELECT tag_title as tagName FROM reports_tags NATURAL JOIN tags WHERE report_id = {$id} GROUP BY tag_id";
        $tags = \DB::select($sql);
        return $tags;
    }

    /**
     * @param Request
     * @param bool
     * @return bool
     */
    public static function addNew($request, $captcha=true)
    {
        if ($captcha) {
            $recaptcha = new ReCaptcha(env('CAPTCHA_SECRET_KEY'));
            $resp = $recaptcha->verify($request->captcha, $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                return "CAPTCHA_ERROR";
            }
        }
        $user = User::getUserByName($request->userName);
        if ($user === false) {
            User::addUser($request->userName, $request->email, $request->url);
        }
        $userId = User::getUserByName($request->userName)->id;

        $report = self::create([
            'user_id' => $userId,
            'text' => $request->text
        ]);

        $reportId = $report->report_id;

        if (!empty($request->tags)) {
            $tags = json_decode($request->tags);
            Tag::addNew($reportId, $tags);
        }
        if ($report !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param mixed object
     * @return bool
     * @throws \Exception
     */
    public static function updateReport($newData) {
        $item = self::find($newData->reportId);
        if (count($item) === 0) {
            return false;
        }

        $item->text = $newData->text;
        $item->updatedAt = self::getCurrentTimeStamp();
        Tag::deleteTagByReportId($item->report_id);

        if (!empty($newData->tags)) {
            $tags = json_decode($newData->tags);
            Tag::addNew($item->report_id, $tags);
        }

        $item->save();

        return true;
    }

    /**
     * @param mixed object
     * @return bool
     * @throws \Exception
     */
    public static function deleteReport($newData)
    {
        $item = self::find($newData->reportId);
        if (count($item) === 0) {
            return false;
        }
        Tag::deleteTagByReportId($item->report_id);
        $item->delete();
        return true;
    }

    /**
     * @param int
     * @throws \Exception
     */
    public static function deleteByUserId($userId)
    {
        $reports = DB::table('reports')->where('user_id', $userId)->get();
        foreach ($reports as $report) {
            Tag::deleteTagByReportId($report->report_id);
            $item = self::find($report->report_id);
            if ($item !== null) {
                $item->delete();
            }
        }
    }

    /**
     * @param mixed object
     * @return mixed array|bool
     * @throws \Exception
     */
    public static function search($params)
    {
        $response = array();
        if (strlen($params->name) === 0) { // Имя не задано
            if (strlen($params->email) === 0) { // Mail не задан, только дата
                $res = self::where('createdAt', 'like', $params->createdAt . '%')->get();
                foreach ($res as $re) {
                    $data = new \stdClass();
                    $currentUser = User::getUserById($re->user_id);
                    $data->id = $re->report_id;
                    $data->tags = self::getTags($data->id);
                    $data->userName = $currentUser->name;
                    $data->email = $currentUser->email;
                    $data->url = $currentUser->url;
                    $data->text = $re->text;
                    $data->createdAt = self::formatDate($re->createdAt);
                    $data->updatedAt = self::formatDate($re->updatedAt);
                    array_push($response, $data);
                }
                return count($response) === 0 ? false : $response;
            } else { // Mail задан
                if (strlen($params->createdAt) === 0) { // Дата не задана, только email
                    $users = User::getUsersByEmail($params->email);
                    if ($users === false) {
                        return false;
                    }
                    foreach ($users as $user) {
                        $res = self::where('user_id', $user->id)->get();
                        foreach ($res as $re) {
                            $data = new \stdClass();
                            $currentUser = \App\User::getUserById($re->user_id);
                            $data->id = $re->report_id;
                            $data->tags = self::getTags($data->id);
                            $data->userName = $currentUser->name;
                            $data->email = $currentUser->email;
                            $data->url = $currentUser->url;
                            $data->text = $re->text;
                            $data->createdAt = self::formatDate($re->createdAt);
                            $data->updatedAt = self::formatDate($re->updatedAt);
                            array_push($response, $data);
                        }
                    }
                    return count($response) === 0 ? false : $response;
                } else { // Дата задана и email
                    $users = User::getUsersByEmail($params->email);
                    if ($users === false) {
                        return false;
                    }
                    foreach ($users as $user) {
                        $res = self::where('user_id', $user->id)->where('createdAt', 'like', $params->createdAt . '%')->get();
                        foreach ($res as $re) {
                            $data = new \stdClass();
                            $currentUser = User::getUserById($re->user_id);
                            $data->id = $re->report_id;
                            $data->tags = self::getTags($data->id);
                            $data->userName = $currentUser->name;
                            $data->email = $currentUser->email;
                            $data->url = $currentUser->url;
                            $data->text = $re->text;
                            $data->createdAt = self::formatDate($re->createdAt);
                            $data->updatedAt = self::formatDate($re->updatedAt);
                            array_push($response, $data);
                        }
                    }
                    return count($response) === 0 ? false : $response;
                }
            }
        } else {
            if (strlen($params->email) === 0) { // Mail не задан
                if (strlen($params->createdAt) === 0) { // Дата не задана, только имя
                    $user = User::getUserByName($params->name);
                    if ($user === false) {
                        return false;
                    }
                    $res = self::where('user_id', $user->id)->get();
                    foreach ($res as $re) {
                        $data = new \stdClass();
                        $currentUser = User::getUserById($re->user_id);
                        $data->id = $re->report_id;
                        $data->tags = self::getTags($data->id);
                        $data->userName = $currentUser->name;
                        $data->email = $currentUser->email;
                        $data->url = $currentUser->url;
                        $data->text = $re->text;
                        $data->createdAt = self::formatDate($re->createdAt);
                        $data->updatedAt = self::formatDate($re->updatedAt);
                        array_push($response, $data);
                    }
                    return count($response) === 0 ? false : $response;
                } else { // Имя и дата
                    $user = User::getUserByName($params->name);
                    if ($user === false) {
                        return false;
                    }
                    $res = self::where('user_id', $user->id)->where('createdAt', 'like', $params->createdAt . '%')->get();
                    foreach ($res as $re) {
                        $data = new \stdClass();
                        $currentUser = User::getUserById($re->user_id);
                        $data->id = $re->report_id;
                        $data->tags = self::getTags($data->id);
                        $data->userName = $currentUser->name;
                        $data->email = $currentUser->email;
                        $data->url = $currentUser->url;
                        $data->text = $re->text;
                        $data->createdAt = self::formatDate($re->createdAt);
                        $data->updatedAt = self::formatDate($re->updatedAt);
                        array_push($response, $data);
                    }
                    return count($response) === 0 ? false : $response;
                }
            } else { // Email задан
                if (strlen($params->createdAt) === 0) { // Дата не задана, только имя и маил
                    $user = User::getUserByNameAndEmail($params->name, $params->email);
                    if ($user === false) {
                        return false;
                    }
                    $res = self::where('user_id', $user->id)->get();
                    foreach ($res as $re) {
                        $data = new \stdClass();
                        $currentUser = User::getUserById($re->user_id);
                        $data->id = $re->report_id;
                        $data->tags = self::getTags($data->id);
                        $data->userName = $currentUser->name;
                        $data->email = $currentUser->email;
                        $data->url = $currentUser->url;
                        $data->text = $re->text;
                        $data->createdAt = self::formatDate($re->createdAt);
                        $data->updatedAt = self::formatDate($re->updatedAt);
                        array_push($response, $data);
                    }
                    return count($response) === 0 ? false : $response;
                } else { // Заданы все 3 параметра
                    $user = User::getUserByNameAndEmail($params->name, $params->email);
                    if ($user === false) {
                        return false;
                    }
                    $res = self::where('user_id', $user->id)->where('createdAt', 'like', $params->createdAt . '%')->get();
                    foreach ($res as $re) {
                        $data = new \stdClass();
                        $currentUser = User::getUserById($re->user_id);
                        $data->id = $re->report_id;
                        $data->tags = self::getTags($data->id);
                        $data->userName = $currentUser->name;
                        $data->email = $currentUser->email;
                        $data->url = $currentUser->url;
                        $data->text = $re->text;
                        $data->createdAt = self::formatDate($re->createdAt);
                        $data->updatedAt = self::formatDate($re->updatedAt);
                        array_push($response, $data);
                    }
                    return count($response) === 0 ? false : $response;
                }
            }
        }
    }

    /**
     * @param int
     * @return mixed array|bool
     * @throws \Exception
     */
    public static function searchByTag($tagId)
    {
        $response = array();
        $tags = DB::table('reports_tags')->where('tag_id', $tagId)->get();
        foreach ($tags as $tag) {
            $report = self::getById($tag->report_id);
            $data = new \stdClass();
            $currentUser = User::getUserById($report->user_id);
            $data->id = $report->report_id;
            $data->tags = self::getTags($data->id);
            $data->userName = $currentUser->name;
            $data->email = $currentUser->email;
            $data->url = $currentUser->url;
            $data->text = $report->text;
            $data->createdAt = self::formatDate($report->createdAt);
            $data->updatedAt = self::formatDate($report->updatedAt);
            array_push($response, $data);
        }
        return count($response) === 0 ? false : $response;
    }

     /**
     *  Форматирует дату. Пример: 02:26 09.03.19
     *
     * @param string
     * @return string
     * @throws \Exception
     */
    private static function formatDate($param)
    {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($param));
        return $date->format('H:i d.m.Y');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getCurrentTimeStamp()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Moscow'));
        return $date->format("Y-m-d H:i:s");
    }

    /*
     *  Столбцы в БД, которые могут добавляться
     */
    protected $fillable = ['user_id', 'text', 'createdAt', 'updatedAt'];

    /*
     *  Отключает автоматические таймштампы
     */
    public $timestamps = false;
    /*
     * Устанавливает по умолчанию primaryKey как report_id
     */
    public $primaryKey = 'report_id';
}
