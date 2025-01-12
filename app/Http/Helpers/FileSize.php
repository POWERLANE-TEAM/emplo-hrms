<?php

namespace App\Http\Helpers;

class FileSize
{
    public static function formatSize($bytes, $precision = 0): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;
    
        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }
    
        return round($bytes, $precision) . ' ' . $units[$index];
    }
}