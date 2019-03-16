<?php

namespace App\Http\Controllers;

use App\Fake;
use App\Report;
use Illuminate\Http\Request;

class FakesController extends Controller
{
    public function make($count)
    {
        if ((int)$count <= 0 || (int)$count > 100) {
            return 'Количество должно быть не больще 100';
        }
        $fakes = Fake::Add($count);
        foreach ($fakes as $fake) {
            $status = Report::addNew($fake, false);
            if ($status === false) {
                echo "Произошла ошибка";
        }
            }
        return redirect('/');
    }
}
