<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class CompressExistingImages extends Command
{
    protected $signature = 'images:compress-existing';
    protected $description = 'Compress dan convert semua gambar produk lama ke WebP';

    public function handle()
    {
        $manager = ImageManager::usingDriver(Driver::class);
        $images = ProductImage::all();

        $bar = $this->output->createProgressBar($images->count());

        foreach ($images as $image) {
            $oldPath = Storage::disk('public')->path($image->path);

            if (! file_exists($oldPath)) {
                $bar->advance();
                continue;
            }

            $img = $manager->decode($oldPath);
            $img->scaleDown(width: 800);

            $newRelativePath = 'products/' . pathinfo($image->path, PATHINFO_FILENAME) . '.webp';
            $newFullPath = Storage::disk('public')->path($newRelativePath);

            $img->encodeUsingFormat(Format::WEBP, quality: 75)->save($newFullPath);

            if ($newRelativePath !== $image->path) {
                Storage::disk('public')->delete($image->path);
                $image->update(['path' => $newRelativePath]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nSelesai convert {$images->count()} gambar ke WebP.");
    }
}