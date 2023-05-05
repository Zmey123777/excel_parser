<?php

namespace App\Http\Controllers;

use App\Models\ExcelFile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelShowController extends Controller
{
    /**
     * Method showing database rows grouped and sorted by date
     * @return array
     */
    public function show(): array
    {
        $rows = ExcelFile::orderBy('date')
            ->get()
            ->groupBy(function($fileRows) {
                return Carbon::parse($fileRows->date)->format('d,m,Y');
            });
        $array = [];
        foreach ($rows as $index => $element) {
            $array[$index] = $element;
        }
        return $array;
    }
}
