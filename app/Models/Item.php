<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use App\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


#[ObservedBy([ItemObserver::class])]
class Item extends Model {

    use Filterable;


    protected $table         = 'items';

    protected $primaryKey    = 'id';

    protected $keyType       = 'int';

    public    $incrementing  = true;

    public    $timestamps    = true;


    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'item_options')
            ->withPivot(['value']);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'item_images');
    }


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
        'id', 'title', 'body',
        'price', 'stock', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
