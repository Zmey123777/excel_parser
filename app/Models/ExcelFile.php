<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    use HasFactory;

    protected $dateFormat = 'd.m.Y';
    protected $table = 'rows';
    protected $fillable = [
        'id',
        'name',
        'date',
    ];
}
