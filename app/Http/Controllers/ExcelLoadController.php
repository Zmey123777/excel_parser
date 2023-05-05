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

    public function __construct(ExcelParser $excelParser)
    {
        $this->excelParser = $excelParser;
    }

    /**
     * Loading and processing xlsx file
     * @param Request $request
     * @return string
     * TBD get actual file storage location
     */
    public function excelLoad(Request $request): string
    {
        // Validate the file type
        $request->validate(['file' => 'mimes:xlsx, csv']);
        // Store file in Laravel storage
        $file = $request->file('file')->store('excel');
        $path = Storage::path($file);
        // Parse file data into the database
        $array = $this->excelParser->parse($path);
        // Progressive file upload
        $chunk = array_chunk($array, 1000);
        foreach ($chunk as $index => $array) {
            ProcessExcelFile::dispatch($this->excelParser, $array);
        }
        /*$redis = new Redis();
        $redis->connect('localhost');
        return $redis->get('test');*/
        File::delete($path);
        return 'Файл загружен!';

    }

}
