<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Model;


class Option extends Model {

    use Filterable;


    protected $table = 'options';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public    $incrementing = true;

    public    $timestamps = true;


    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    protected $with = [
        //
    ];

    protected $appends = [
        //
    ];

    protected $fillable = [
        'id', 'abbr', 'title',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
