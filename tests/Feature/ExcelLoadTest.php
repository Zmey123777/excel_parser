<?php

namespace Tests\Feature;

use App\Http\Controllers\ExcelLoadController;
use App\Interfaces\ExcelParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExcelLoadTest extends TestCase
{
    use WithoutMiddleware, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_load_file_form_is_accessible()
    {
        $response = $this->withoutMiddleware()->get('/excel');
        $response->assertStatus(200);
    }
    public function test_file_load_request()
    {
       // $file = Storage::path('excel/7xfrCDzztfXQ7ETOqEYjHE0Er7wtSm2Uo4HSUCEl.xlsx');
       Storage::fake('excel');
        $file = UploadedFile::fake()->create('unit.xlsx',200);
        $response = $this->post('/upload-file', [
            'file' => $file,
        ])->withHeaders(['attachment']);
        Storage::disk('local')->assertExists('excel/unit.xlsx');
    }
}
