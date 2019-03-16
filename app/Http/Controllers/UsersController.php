<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class UsersController extends Controller
{

    public function showUser($url)
    {
        $user = App\User::getUrl($url);
        return view('user.one', ['user' => $user]);
    }

    public function updateName(Request $request)
    {
        header('Content-Type: application/json');
        $response = App\User::updateName((object)$request);
        echo json_encode($response);
    }

    public function updateEmail(Request $request)
    {
        header('Content-Type: application/json');
        $response = App\User::updateEmail((object)$request);
        echo json_encode($response);
    }

    public function updateUrl(Request $request)
    {
        header('Content-Type: application/json');
        $response = App\User::updateUrl((object)$request);
        echo json_encode($response);
    }

    public static function getUser($userName)
    {
        header('Content-Type: application/json');
        echo json_encode(App\User::getUserByName($userName));
    }

    public static function getUrl($url)
    {
        header('Content-Type: application/json');
        echo json_encode(App\User::getUrl($url));
    }

    public static function deleteUser(Request $request)
    {
        header('Content-Type: application/json');
        $response = App\User::deleteUser($request->userId);
        echo json_encode($response);
    }
}
