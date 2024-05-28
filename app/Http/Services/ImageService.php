<?php

namespace App\Http\Services;

use App\Exceptions\ImageNotFoundException;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;


class ImageService {

    /**
     * @param int $id
     * @return Image
     * @throws ImageNotFoundException
     */
    public function show(int $id): Image
    {
        $image = Image::find($id);

        if (!$image) {
            throw new ImageNotFoundException();
        }

        return $image;
    }

    /**
     * @param array $data
     * @return Image
     */
    public function store(array $data): Image
    {
        $file = $data['file'];
        $fileName = Image::generateFilename();
        $fileExt = $file->extension();
        Storage::disk('public')
            ->put($data['path'] . DIRECTORY_SEPARATOR . $fileName . '.' . $fileExt,
                file_get_contents($file));

        return Image::create([

            'path' => $data['path'],
            'filename' => $fileName,
            'ext' => $fileExt,
            'size' => $file->getSize(),
        ]);
    }

    /**
     * @param int $id
     * @return void
     * @throws ImageNotFoundException
     */
    public function destroy(int $id): void
    {
        $image = Image::find($id);

        if (!$image) {
            throw new ImageNotFoundException();
        }

        $filePath = $image->full_path;

        if (Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->exists($filePath)) {
            Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->delete($filePath);
        }

        $image->delete();
    }

}
