<?php

namespace App\Jobs;

use App\Interfaces\ExcelParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessExcelFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ExcelParser
     */
    private $excelParser;
    /**
     * @var mixed
     */
    private $array;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ExcelParser $excelParser, $array)
    {
        $this->excelParser = $excelParser;
        $this->array = $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->excelParser->store($this->array);
    }
}
