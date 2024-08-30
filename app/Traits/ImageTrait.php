<?php

namespace App\Traits;
trait ImageTrait
{

    public function upload_image($image) :string
    {
        $photoName = uniqid() . '.' . $image->extension();
        $image->move(public_path("images/"), $photoName);  
        return $photoName;
    }

    public function delete_image($image) :bool
    {
        $oldPhotoPath = public_path("images/$image");
        if (file_exists($oldPhotoPath)) {
            unlink($oldPhotoPath);
            return true;
        }
        return false;
    }
}