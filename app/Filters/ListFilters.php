<?php

namespace App\Filters;

class ListFilters
{
    protected $filters = [
        'name' => NameFilter::class
    ];

    public function apply($query)
    {
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];
            $query = $filterInstance($query, $value);
        }

        return $query;
    }

    public function receivedFilters()
    {
        return request()->only(array_keys($this->filters));
    }
}
