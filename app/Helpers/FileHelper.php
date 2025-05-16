<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FileHelper
{
    /**
     * Delete a file from storage if it exists.
     *
     * @param string $directory
     * @param string|null $filename
     * @return void
     */
    public static function deleteFile(string $directory, ?string $filename): void
    {
        if ($filename && Storage::exists($directory . '/' . $filename)) {
            Storage::delete($directory . '/' . $filename);
        }
    }

    public static function convertTo12Hour($time)
    {
        return Carbon::createFromFormat('H:i', $time)->format('g:i A');
    }
}
