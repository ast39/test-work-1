<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class OptionScope extends AbstractFilter {

    public const Q = 'q';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [
            self::Q => [$this, 'q'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where('title', 'like', '%'.$value.'%');
    }
}
