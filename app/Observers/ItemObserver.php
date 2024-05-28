<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;


class ItemObserver {

    public function deleting(Item $item): void
    {
        foreach ($item->images as $image) {
            $filePath = $image->full_path;

            if (Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->exists($filePath)) {
                Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->delete($filePath);
            }
        }
    }
}
