<?php
namespace App\Services\Traits;

use Illuminate\Support\Facades\Storage;

trait SaveRecordTrait {
    function saveRecordFile($bas64_data){
        $mimes = new \Mimey\MimeTypes;
        $mime = mime_content_type($bas64_data);
        $extension = $mimes->getExtension($mime);
        $bas64_data = str_replace("data:application/octet-stream;base64,","",$bas64_data);
        $bas64_data = str_replace("data:".$mime.";base64,","",$bas64_data);
        $bas64_data = base64_decode($bas64_data);
        $name = 'record'.time().'.'.$extension;
        $name = 'uploads/records/'.$name;
        Storage::disk('public')->put($name, $bas64_data);
        return $name;
    }
    function saveTryingUserImage($bas64_data){
        $mimes = new \Mimey\MimeTypes;
        $mime = mime_content_type($bas64_data);
        $extension = $mimes->getExtension($mime);
        $bas64_data = str_replace("data:application/octet-stream;base64,","",$bas64_data);
        $bas64_data = str_replace("data:".$mime.";base64,","",$bas64_data);
        $bas64_data = base64_decode($bas64_data);
        $name = 'image'.time().'.'.$extension;
        $name = 'uploads/trying/users/'.$name;
        Storage::disk('public')->put($name, $bas64_data);
        return $name;
    }
    function deleteRecordFile($record){
        if($record){
            !is_null($record->file_path) && Storage::disk('public')->delete($record->file_path);
            // Storage::disk('public')->delete($record->file_path);
        }
    }
}
