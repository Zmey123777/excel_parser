<?php

namespace App\Services;

use App\Interfaces\ExcelParser;
use App\Models\ExcelFile;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use DateTime;
use DateTimeZone;
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
        $isFirst = true;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // Avoiding reading header element
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }
                $value = $row->toArray();
                $id = $value[0];
                $name = $value[1];
                $date = $value[2];
                $dateToTimestamp = DateTime::createFromFormat('j.n.y', $date,  new DateTimeZone('Asia/Novosibirsk'));
                $dateToTimestamp = $dateToTimestamp->format('Y-m-d');

                $array[] = [
                    'id' => $id,
                    'name' => $name,
                    'date' => $dateToTimestamp,
                ];
            }
        }
        return $array;
    }

    /**
     * Method that saves the required fields into the MySQL database
     * @param $array
     * @return void
     * TBD check methods logic
     */
    public function store($array): void
    {
        foreach ($array as $element) {
            DB::table('rows')->updateOrInsert([
                'id' => $element['id'],
                'name' => $element['name'],
                'date' => $element['date'],
            ]);
        }
    }
}
