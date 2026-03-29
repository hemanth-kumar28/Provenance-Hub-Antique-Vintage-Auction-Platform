<?php
namespace App\Traits;

/**
 * Processes images using PHP native GD Library.
 * Enforces memory management (imagedestroy).
 */
trait GDImageProcessor {

    /**
     * Resizes, resamples, and optionally applies grayscale filter and watermark.
     */
    protected function processImage(string $sourcePath, string $destinationPath, int $maxWidth, int $maxHeight, bool $grayscale = false): bool {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $info = getimagesize($sourcePath);
        if ($info === false) return false;

        $mime = $info['mime'];
        $sourceImage = null;

        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$sourceImage) {
            return false;
        }

        $origWidth = $info[0];
        $origHeight = $info[1];

        // Aspect Ratio logic
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG/WebP
        if ($mime == 'image/png' || $mime == 'image/webp') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        // Resample
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Apply visual filter
        if ($grayscale) {
            imagefilter($newImage, IMG_FILTER_GRAYSCALE);
        }

        // Add Watermark
        $watermarkText = "Provenance Hub";
        $textColor = imagecolorallocatealpha($newImage, 255, 255, 255, 75);
        imagestring($newImage, 5, $newWidth - 130, $newHeight - 20, $watermarkText, $textColor);

        $saved = false;
        switch ($mime) {
            case 'image/jpeg':
                $saved = imagejpeg($newImage, $destinationPath, 85);
                break;
            case 'image/png':
                $saved = imagepng($newImage, $destinationPath, 8);
                break;
            case 'image/webp':
                $saved = imagewebp($newImage, $destinationPath, 85);
                break;
        }

        // CRITICAL: Explicitly destroy resources to prevent memory leaks
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $saved;
    }
}
