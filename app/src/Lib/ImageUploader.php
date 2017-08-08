<?php

namespace Lib;

use Core\Config;
use Eventviva\ImageResize;

class ImageUploader
{

    public static function uploadImage(array $files, int $maxWidth = 320, int $maxHeight = 240)
    {
        $result = '';
        if (!$files['image']['error']) {
            $uploadDirectory = Config::get('image_upload_directory');
            mkdir($uploadDirectory, 0777);
            $fileExtension = '.' . pathinfo($files['image']['name'])['extension'];
            $fileName = time() . $fileExtension;
            $filePath = $uploadDirectory . $fileName;

            $image = new ImageResize($files['image']['tmp_name']);
            $imageWidth = $image->getSourceWidth();
            $imageHeight = $image->getSourceHeight();

            if ($imageWidth > $maxWidth) {
                $image->resizeToWidth($maxWidth);
            }

            if ($imageHeight > $maxHeight) {
                $image->resizeToHeight($maxHeight);
            }

            if ($image->save($filePath)) {
                $result = $fileName;
            }
        }

        return $result;
    }
}