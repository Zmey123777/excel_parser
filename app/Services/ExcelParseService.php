<?php

namespace App\Services;

use App\Interfaces\ExcelParser;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\DB;

class ExcelParseService implements ExcelParser
{

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

    public function save($array): void
    {
        $isFirst = true;
        foreach ($array as $element) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }
            DB::table('my_rows')->insert([
                'id' => $element['id'],
                'name' => $element['name'],
                'date' => $element['date']
            ]);
        }
    }
}
