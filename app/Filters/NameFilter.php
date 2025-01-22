<?php

namespace App\Filters;

class NameFilter
{
    public function __invoke($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
