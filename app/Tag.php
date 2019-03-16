<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App;

class Tag extends Model
{
    /**
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        $tags = DB::table('tags')->groupBy('tag_title')->get();
        return $tags;
    }

    /**
     * @param string
     * @return bool|int
     */
    public static function getTagIdByName($tagName)
    {
        $tag = DB::table('tags')->where('tag_title', $tagName)->first();
        if ($tag === null) {
            return false;
        }
        return $tag->tag_id;
    }

    /**
     * @param int
     * @return bool|Tag
     */
    public static function getById($id)
    {
        $tag = DB::table('tags')->where('tag_id', $id)->first();
        if ($tag === null) {
            return false;
        }
        return $tag;
    }

    /**
     * @param int
     * @param string array
     */
    public static function addNew($reportId, $tags)
    {
        foreach ($tags as $tag) {
            $tagId = self::getTagIdByName($tag);
            if ($tagId === false) {
                $newTag = self::createTag($tag);
                self::linkTagWithReport($reportId, $newTag);
            } else {
                self::linkTagWithReport($reportId, $tagId);
            }
        }
    }

    /**
     * @param int
     * @param int
     */
    public static function linkTagWithReport($reportId, $tagId)
    {
        $tag = DB::table('reports_tags')->where('report_id', $reportId)->where('tag_id', $tagId)->first();
        if ($tag !== null) {
            return;
        }
        DB::table('reports_tags')->insert([
            'report_id' => $reportId,
            'tag_id' => $tagId
        ]);
    }

    /**
     * @param string
     * @return int
     */
    public static function createTag($tagName)
    {
        DB::table('tags')->insert([
            'tag_title' => $tagName
        ]);
        return DB::table('tags')->where('tag_title', $tagName)->first()->tag_id;
    }

    /**\
     * @param int
     */
    public static function deleteTagByReportId($reportId)
    {
        DB::table('reports_tags')->where('report_id', $reportId)->delete();
    }

    public $timestamps = false;
    public $primaryKey = 'tag_id';
}
