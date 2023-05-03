<?php

namespace App\Interfaces;

interface ExcelParser
{
    public function parse(string $path): array;
    public function store(array $array): void;
}
