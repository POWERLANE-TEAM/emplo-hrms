<?php

namespace App\Http\Helpers;

class FilepondHelper
{
    public static function parseAccept($accept)
    {
        if (is_array($accept)) {
            $result = [];
            foreach ($accept as $prefix => $types) {
                if (is_array($types)) {
                    foreach ($types as $type) {
                        $result[] = "$prefix/$type";
                    }
                } else {
                    $result[] = "$prefix/$types";
                }
            }
            return implode(', ', $result);
        }
        return $accept;
    }

    public static function  transfromToFile($filePath)
    {
        try {
            if (file_exists($filePath)) {

                $tempFile = new \Illuminate\Http\UploadedFile(
                    $filePath,
                    basename($filePath),
                    null,
                    null,
                    true
                );

                return $tempFile;
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }
}
