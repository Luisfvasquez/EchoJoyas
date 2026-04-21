<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function storeOptimizedWithThumbnail(UploadedFile $file): array
    {
        $imagePath = $file->store('products', 'public');
        $thumbnailPath = $this->createThumbnail($imagePath);

        return [
            'image_path' => $imagePath,
            'thumbnail_path' => $thumbnailPath,
        ];
    }

    public function deletePaths(?string $imagePath = null, ?string $thumbnailPath = null): void
    {
        if (!empty($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        if (!empty($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }

    private function createThumbnail(string $relativePath, int $maxSize = 480): ?string
    {
        $disk = Storage::disk('public');
        $absolutePath = $disk->path($relativePath);

        if (!is_file($absolutePath)) {
            return null;
        }

        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        $source = match ($extension) {
            'jpg', 'jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($absolutePath) : null,
            'png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($absolutePath) : null,
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : null,
            default => null,
        };

        if (!$source) {
            return null;
        }

        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);

        if ($srcWidth <= 0 || $srcHeight <= 0) {
            imagedestroy($source);
            return null;
        }

        if ($srcWidth > $srcHeight) {
            $newWidth = $maxSize;
            $newHeight = (int) round(($srcHeight * $maxSize) / $srcWidth);
        } else {
            $newHeight = $maxSize;
            $newWidth = (int) round(($srcWidth * $maxSize) / $srcHeight);
        }

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);

        $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
        imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled(
            $thumbnail,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $srcWidth,
            $srcHeight
        );

        $dir = dirname($relativePath);
        $name = pathinfo($relativePath, PATHINFO_FILENAME);

        $thumbnailRelativePath = ($dir === '.' ? '' : $dir . '/') . 'thumbs/' . $name . '.webp';
        $thumbnailAbsolutePath = $disk->path($thumbnailRelativePath);

        if (!is_dir(dirname($thumbnailAbsolutePath))) {
            mkdir(dirname($thumbnailAbsolutePath), 0755, true);
        }

        $saved = function_exists('imagewebp')
            ? imagewebp($thumbnail, $thumbnailAbsolutePath, 82)
            : false;

        imagedestroy($source);
        imagedestroy($thumbnail);

        return $saved ? $thumbnailRelativePath : null;
    }
}