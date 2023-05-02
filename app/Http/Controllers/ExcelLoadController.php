<?php

namespace App\Http\Controllers;

use App\Interfaces\ExcelParser;
use App\Models\ExelFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;


class ExcelLoadController extends Controller
{
    /**
     * @var ExcelParser
     */
    private $excelParser;

    public function __construct(ExcelParser $excelParser)
    {
        $this->excelParser = $excelParser;
    }
    public function excelLoad(\Illuminate\Http\Request $request)
    {
        $fileInfo = $request->file->getClientOriginalName();
        //$body = $request->file->getContent();
        $date = microtime(false);
        $data = [
            'name' => $fileInfo,
            'date' => $date,
        ];
        $path = Storage::path('excel.xlsx');
        $array = $this->excelParser->parse($path);
        $this->excelParser->save($array);
        dd($array);

        //Storage::put('exel.xlsx',$body);

        return $data;
    }
}
