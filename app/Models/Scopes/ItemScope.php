<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class ItemScope extends AbstractFilter {

    public const Q = 'q';

    public const STATUS = 'status';

    public const OPTIONS = 'options';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::STATUS => [$this, 'status'],
            self::OPTIONS => [$this, 'options'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where(function($query) use ($value) {
            $query->where('title', 'like', '%' . $value . '%')
                ->orWhere('body', 'like', '%' . $value . '%');
        });
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }

    public function options(Builder $builder, $options): void
    {
        foreach ($options as $option => $values) {
            $builder->whereHas('options', function($q) use ($option, $values) {
                $q->where('abbr', $option)
                    ->whereIn('value', $values);
            });
        }
    }
}
