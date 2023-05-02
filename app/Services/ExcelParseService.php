<?php

namespace App\Services;

use App\Interfaces\ExcelParser;
use App\Models\ExcelFile;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Illuminate\Support\Facades\DB;

/**
 * Class implementation of Excel file parsing
 */
class ExcelParseService implements ExcelParser
{

    /**
     * Method that reads the file and prepare the array to processing
     * @param string $path
     * @return array
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function parse(string $path): array
    {
        $array = [];
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->setShouldFormatDates(true);
        $reader->open($path);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $value = $row->toArray();
                $id = $value[0];
                $name = $value[1];
                $date = $value[2];
                $array[] = [
                    'id' => $id,
                    'name' => $name,
                    'date' => $date,
                ];
            }
        }
        return $array;
    }

    /**
     * Method that saves the required fields in the database
     * @param $array
     * @return void
     * TBD check methods logic
     */

    public function save($array): void
    {
        $lastId = DB::table('rows')
            ->select('id')
            ->orderBy('id','desc')
            ->limit(1)
            ->get()->all();
        //dd($lastId[0]->id);
        $isFirst = true;
        foreach ($array as $element) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }
            if($lastId) {
                if ($element['id'] == $lastId[0]->id) return;
            }
            DB::table('rows')->updateOrInsert([
                'id' => $element['id'],
                'name' => $element['name'],
                'date' => $element['date']
            ]);
        }
    }
}
