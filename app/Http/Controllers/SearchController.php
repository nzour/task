<?php

namespace App\Http\Controllers;

use App\Report;
use App\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $response = Report::search((object)$request);
        return $response === false ? json_encode(false) : view('report.found-all', ['reports' => $response]);
    }

    public function tag($tagId = "")
    {
        if (strlen($tagId) === 0) {
            return json_encode(false);
        }
        $response = Report::searchByTag($tagId);
        return $response === false ? json_encode(false) : view('report.found-all', ['reports' => $response]);
    }

    public function tagByName($tagName = "")
    {
        if (strlen($tagName) === 0) {
            return json_encode(false);
        }
        $tagId = Tag::getTagIdByName($tagName);
        if ($tagId === false) {
            return json_encode(false);
        }
        $response = Report::searchByTag($tagId);
        return $response === false ? json_encode(false) : view('report.found-all', ['reports' => $response]);
    }
}
