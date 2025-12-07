<?php

namespace App\services\v1;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class RequestFilter
{
    protected Builder $builder;

    protected Request $request;

    protected array $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $name => $value) {
            if(method_exists($this, $name))
            {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function filter(array $arr): Builder
    {

        foreach ($arr as $name => $value) {
            if(method_exists($this, $name))
            {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function sort(string $value): Builder
    {

        $columns = explode("," , $value);

        foreach ($columns as $column) {

            if(!in_array($column, $this->sortable)) {
                continue;
            }

            if(Str::startsWith($column, '-')){
                $this
                    ->builder
                    ->orderBy(Str::chopStart($column, '-'),'desc');
            }else{
                $this->builder->orderBy($column, 'asc');
            }

        }

        return $this->builder;
    }


}
