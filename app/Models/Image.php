<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Image extends Model {

    use Filterable;


    protected $table         = 'images';

    protected $primaryKey    = 'id';

    protected $keyType       = 'int';

    public    $incrementing  = true;

    public    $timestamps    = true;


    public static function generateFilename(): string
    {
        return uniqid();
    }

    public function getFullPathAttribute(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->filename . '.' . $this->ext;
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES', 'public'))
            ->url($this->path . DIRECTORY_SEPARATOR . $this->filename . '.' . $this->ext);
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
        'full_path',
        'url',
    ];

    protected $fillable = [
        'id', 'path', 'filename', 'ext', 'size',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
