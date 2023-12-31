<?php

use App\Models\File;
use Illuminate\Support\Str;

function uploadFile($file, $path){
    $clientOriginalName = $file->getClientOriginalName();
    $clientExtention = $file->getClientOriginalExtension();

    $fileName = auth()->id() . '_' . Str::uuid()->toString() . '.' . $clientExtention;  

    $directory = public_path('uploads/'.$path);

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $file->move($directory, $fileName);

    $file = File::create([
        'path' => $path,
        'original_name' => $clientOriginalName,
        'current_name' => $fileName
    ]);

    $file->save();

    return $file;
}

function removeFile($file){
    if($file != null){
        $filePath = public_path('uploads/').$file->path.'/'.$file->current_name;

        if(file_exists($filePath) == true) unlink($filePath);

        $file->delete();
    }
}