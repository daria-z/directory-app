<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function getPerPage(\Illuminate\Http\Request $request, int $default = 15, int $max = 100): int
    {
        return min((int) $request->query('per_page', $default), $max);
    }
}
