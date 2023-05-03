<?php

namespace App\Http\Controllers;

use App\Interfaces\ExcelParser;
use App\Jobs\ProcessExcelFile;
use App\Models\ExcelFile;
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

    /**
     * Loading and Processing xlsx file
     * @param Request $request
     * @return string
     * TBD get actual file storage location
     */
    public function excelLoad(\Illuminate\Http\Request $request)
    {   // Store file in Laravel storage
        $file = $request->file('file')->store('excel');
        $path = Storage::path($file);
        // Parse file data into the database
        $array = $this->excelParser->parse($path);
        $chunk = array_chunk($array, 1000);
        foreach ($chunk as $index => $array) {
            ProcessExcelFile::dispatch($this->excelParser, $array);
        }

        return 'Файл сохранен!';
    }
}
