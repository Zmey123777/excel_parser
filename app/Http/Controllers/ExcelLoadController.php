<?php

namespace App\Http\Controllers;

use App\Interfaces\ExcelParser;
use App\Jobs\ProcessExcelFile;
use App\Models\ExcelFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Redis;


class ExcelLoadController extends Controller
{
    /**
     * @var ExcelParser
     */
    private $excelParser;
    /**
     * @var Request
     */
    private $request;

    public function __construct(ExcelParser $excelParser, Request $request)
    {
        $this->excelParser = $excelParser;
        $this->request = $request;
    }

    /**
     * Load Excel file
     * @return string
     */

    public function fileLoad(): string
    {
        $request = $this->request;
        // Validate the file type
        $request->validate(['file' => 'max:400|mimes:xlsx, csv']);
        // Store file in Laravel storage
        $file = $request->file('file')->store('excel');
        return Storage::path($file);
    }

    /**
     * Processing xlsx file
     * @return string
     */
    public function excelParse(): string
    {
        $path = self::fileLoad();
        $array = $this->excelParser->parse($path);
        // Progressive file import into the MySQL database
        $chunk = array_chunk($array, 1000);
        foreach ($chunk as $index => $array) {
            ProcessExcelFile::dispatch($this->excelParser, $array);
        }
        //Storage::delete($path);
        return $path;
    }

}
