<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App;

class User extends Model
{
    /**
     * @return User[]
     */
    public static function getAllUsers()
    {
        return self::all();
    }

    /**
     * @param int
     * @return User|bool
     */
    public static function getUserById($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return $user === null ? false : $user;
    }

    /**
     * @param string
     * @return bool|User
     */
    public static function getUserByName($name)
    {
        $user = DB::table('users')->where('name', $name)->first();
        return $user === null ? false : $user;
    }

    /**
     * @param string
     * @return bool|User
     */
    public static function getUrl($url)
    {
        $user = DB::table('users')->where('url', $url)->first();
        return $user === null ? false : $user;
    }

    /**
     * @param string
     * @return bool|User[]
     */
    public static function getUsersByEmail($email)
    {
        $users = DB::table('users')->where('email', $email)->get();
        return count($users) === 0 ? false : $users;
    }

    /**
     * @param string
     * @param string
     * @return bool|User
     */
    public static function getUserByNameAndEmail($name, $email)
    {
        $user = DB::table('users')->where('name', $name)->where('email', $email)->first();
        return $user === null ? false : $user;
    }

    /**
     * @param string
     * @param string
     * @param string
     */
    public static function addUser($name, $email, $url)
    {
        if (empty($url)) {
            $url = self::makeUniqUrl();
        }
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'url' => $url
        ]);
    }

    /**
     * @param mixed array
     * @return bool|string
     */
    public static function updateName($data)
    {
        $user = self::find($data->id);
        if ($user === null) {
            return false;
        }
        $user->name = $data->name;
        $user->save();
        return $user->name;
    }

    /**
     * @param mixed array
     * @return bool|string
     */
    public static function updateEmail($data)
    {
        $user = self::find($data->id);
        if ($user === null) {
            return false;
        }
        $user->email = $data->email;
        $user->save();
        return $user->email;
    }

    /**
     * @param mixed array
     * @return bool|string
     */
    public static function updateUrl($data)
    {
        $user = self::find($data->id);
        if ($user === null) {
            return false;
        }
        $user->url = $data->url;
        $user->save();
        return $user->url;
    }

    /**
     * @param int
     * @return bool
     * @throws \Exception
     */
    public static function deleteUser($id)
    {
        $user = self::find($id);
        if ($user === false) {
            return false;
        }
        Report::deleteByUserId($id);
        $user->delete();
        return true;
    }
    /**
     * Генерирует рандомный уникальный url для пользователя
     * @return string
     */
    public static function makeUniqUrl()
    {
        do {
            $url = 'id_' . substr(uniqid(), 7);
        } while (DB::table('users')->where('url', $url)->count() !== 0);
        return $url;
    }

    protected $fillable = ['name', 'email', 'url'];
    public $timestamps = false;
}
