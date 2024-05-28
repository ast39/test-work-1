<?php

namespace App\Models\Scopes\Filter;

use Illuminate\Database\Eloquent\Builder;


trait Filterable {

    /**
     * @param Builder $builder
     * @param FilterInterface $filter
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, FilterInterface $filter)
    {
        $filter->apply($builder);

        return $builder;
    }
}
