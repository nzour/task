<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getAll()
    {
        header('Content-Type: application/json');
        $tags = Tag::getAll();
        if (strlen($tags) === 0) {
            echo json_encode(false);
        } else {
            echo json_encode($tags);
        }
    }
}
