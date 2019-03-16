<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fake extends Model
{
    /**
     * @param int
     * @return mixed array
     */
    public static function Add($count)
    {
        $response = array();
        $faker = \Faker\Factory::create("Ru_Ru");
        for ($i = 0; $i < $count; $i++) {
            $data = new \stdClass;
            $data->userName = $faker->unique()->firstName;
            $data->email = $faker->email;
            $data->url = "";
            $data->tags = array();
            $data->text = $faker->realText();
            $tagsCount = rand(0, 5);
            for ($j = 0; $j < $tagsCount; $j++) {
                array_push($data->tags, $tagName = self::generateRandomTag());
            }
            $data->tags = json_encode($data->tags);
            array_push($response, $data);
        }
        return $response;
    }

    /**
     * @return string
     */
    public static function generateRandomTag()
    {
        $chance = rand(1, 4);
        $tags = Tag::getAll();
        if ($tags->count() > 1) {
            switch ($chance) {
                case 1 :
                    $index = array_rand($tags->all());
                    return $tagName = $tags->all()[$index]->tag_title;
                case 2 :
                case 3 :
                case 4 :
                    return $tagName = self::generateTagName();
            }
        } else {
            return $tagName = self::generateTagName();
        }
    }

    /**
     * @return string
     */
    public static function generateTagName()
    {
        $faker = \Faker\Factory::create();
        $str = $faker->text;
        $arr = explode(' ', $str);
        $index = array_rand($arr);
        return $arr[$index];
    }

}
