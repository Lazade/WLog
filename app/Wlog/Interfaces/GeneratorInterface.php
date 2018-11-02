<?php

namespace App\Wlog\Interfaces;


interface GeneratorInterface
{
    public function generatorFail($error);
    public function generatorSuccess($model);
}