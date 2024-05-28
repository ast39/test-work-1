<?php

namespace App\Models\Scopes\Filter;

use Illuminate\Database\Eloquent\Builder;


interface FilterInterface {

    public function apply(Builder $builder);
}
