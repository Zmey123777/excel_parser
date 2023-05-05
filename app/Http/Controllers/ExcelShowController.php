<?php

namespace App\Http\Controllers;

use App\Models\ExcelFile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelShowController extends Controller
{
    public function show()
    {
        $rows = ExcelFile::orderBy('date')
            ->get()
            ->groupBy(function($files) {
                return Carbon::parse($files->date)->format('d,m,Y');
            });
        $array = [];
        foreach ($rows as $index => $element) {
            $array[$index] = $element;
        }
        return $array;
    }
}
