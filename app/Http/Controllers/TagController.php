<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getAll()
    {
        $tags = Tag::getAll();
        if (strlen($tags) === 0) {
            echo "Нет тегов";
        } else {
            $html = "";
            $html .= "<select class='custom-select'>";
            $html .= "<option selected value='none'>Выберите тег</option>";
            foreach ($tags as $tag) {
                $html .= "<option value='{$tag->tag_id}'>{$tag->tag_title}</option>";
            }
            $html .= "</select>";
            echo $html;
            ;
        }
    }
}
