<?php

namespace Database\Factories\Traits;

use App\Models\Question;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

trait FillsWithMedia
{
    /**
     * Membuat file gambar dummy menggunakan GD dan menyimpannya ke Media Library.
     * * @param Question $question
     * @param string $collectionName
     * @param string $filename
     * @return string|null ULID dari Media yang baru disimpan
     */
    protected function createDummyMedia(Question $question, string $collectionName, string $filename): ?string
    {
        // Path sementara untuk menyimpan gambar yang dibuat GD
        $tempPath = Storage::disk('public')->path('temp/' . $filename);
        
        // Pastikan direktori sementara ada
        if (!Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->makeDirectory('temp');
        }

        // 1. Buat Gambar Dummy dengan GD Library
        // Ukuran: 200x200, warna acak, teks acak
        $width = 200;
        $height = 200;
        $image = imagecreate($width, $height);
        
        // Warna latar belakang acak
        $r = rand(100, 255);
        $g = rand(100, 255);
        $b = rand(100, 255);
        $bgColor = imagecolorallocate($image, $r, $g, $b);
        
        // Warna teks (kontras)
        $textColor = imagecolorallocate($image, 255 - $r, 255 - $g, 255 - $b);
        
        imagestring($image, 5, 20, 90, "Option Media", $textColor);
        imagestring($image, 3, 20, 110, $filename, $textColor);
        
        // Simpan gambar ke path sementara
        imagepng($image, $tempPath);
        imagedestroy($image);
        
        try {
            // 2. Simpan Gambar ke Spatie Media Library
            $media = $question->addMedia($tempPath)
                ->preservingOriginal()
                ->toMediaCollection($collectionName);
                
            // Hapus file sementara
            // unlink($tempPath);
            
            return $media->id; // Kembalikan ULID
            
        } catch (FileCannotBeAdded $e) {
            // Jika gagal, log error dan kembalikan null
            Log::error("Failed to add dummy media: " . $e->getMessage());
            return null;
        }
    }
}