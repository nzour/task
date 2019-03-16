<?php

namespace App\Http\Controllers;

use App\Exceptions\MyExceptions\NotFoundReportByIdException;
use App\Exceptions\MyExceptions\NotFoundReportsException;
use App\Report;

class ReportsController extends Controller
{
    public function index() {
        try {
            return view('report.all', [
                'reports' => Report::findAll()
            ]);
        }
        catch (NotFoundReportsException $ex) {
            return view('report.exception', ['exception' => $ex]);
        }
    }

    public function showById($id)
    {
        try {
            $report = Report::findById($id);
            return view('report.one', [
                'report' => $report
            ]);
        }
        catch (NotFoundReportByIdException $ex) {
            return view('report.exception', ['exception' => $ex]);
        }
    }

    public function showByIdChange($id)
    {
        try {
            $report = Report::findById($id);
            return view('report.one-change', [
                'report' => $report
            ]);
        }
        catch (NotFoundReportByIdException $ex) {
            return view('report.exception', ['exception' => $ex]);
        }
    }
}
