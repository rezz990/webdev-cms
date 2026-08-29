<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
class ImageUploadService { public function store(UploadedFile $image, string $directory): string { return $image->store($directory, 'public'); } }
