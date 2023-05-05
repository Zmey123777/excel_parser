<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    use HasFactory;

    protected $table = 'rows';

    protected $fillable = [
        'id',
        'name',
        'date',
    ];
}
