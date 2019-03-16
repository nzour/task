<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;

class ReportsModifyController extends Controller
{
    public function addNew(Request $request) {
        $response = Report::addNew((object)($request->all()));
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function update(Request $request) {
        $response = Report::updateReport((object)($request->all()));
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function delete(Request $request) {
        header('Content-Type: application/json');
        try {
            $response = Report::deleteReport((object)($request->all()));
        } catch (\Exception $ex) {
            echo false;
        }
        echo json_encode($response);
    }
}
