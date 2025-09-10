<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload a file (image or PDF) and delete old file if provided.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param string|null $oldFilePath
     * @return string Path of the stored file
     */
    public static function saveFile($file, $folder = 'files', $oldFilePath = null)
    {
        $disk = 'public';

        // Ensure folder exists
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder, 0755, true);
        }

        // Delete old file if provided and exists
        if ($oldFilePath && Storage::disk($disk)->exists($oldFilePath)) {
            Storage::disk($disk)->delete($oldFilePath);
        }

        // Store new file
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $file->storeAs($folder, $filename, $disk);

        return $folder . '/' . $filename;
    }
}
