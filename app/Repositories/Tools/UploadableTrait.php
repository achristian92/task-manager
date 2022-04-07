<?php


namespace App\Repositories\Tools;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadableTrait
{
    public function handleUploadedImage(UploadedFile $file,$folder = 'general', $disk = 'public')
    {
        $nameslug = Str::slug($file->getClientOriginalName());
        $name = Str::of($nameslug)->basename($file->clientExtension());
        $filename = $name.'-'.Str::random(4).'.'.$file->clientExtension();
        $filePath = "$folder/" . $filename;
        Storage::disk('s3')->put($filePath, file_get_contents($file),$disk);

        return  Storage::disk('s3')->url($filePath);
    }

    public function handleUploadedDocument(UploadedFile $file,$folder = 'documents', $disk = 'public')
    {
        $nameslug = Str::slug($file->getClientOriginalName());
        $name = Str::of($nameslug)->basename($file->clientExtension());
        $filename = $name.'-'.Str::random(4).'.'.$file->clientExtension();
        $filePath = "$folder/" . $filename;
        Storage::disk('s3')->put($filePath, file_get_contents($file),$disk);

        return  Storage::disk('s3')->url($filePath);
    }

    public function getFullNameFile(UploadedFile $file): string
    {
        $file_name = $file->getClientOriginalName();
        return Str::of($file_name)->basename('.'.$file->clientExtension());
    }

}
