<?php

namespace CrashBandicoot\filemanager\storage;

use Imagick;
use yii\web\UploadedFile;

class LocalStorage extends BaseStorage implements StorageInterface
{

    public function save(UploadedFile $file, $category)
    {
        $name = $this->generateName($file->getBaseName(), true) . '.' . $file->getExtension();
        $path = $this->generatePath($name, $category['path']);

        $success = $file->saveAs($path);

        return $success ? $name : false;
    }

    public function saveBlob(Imagick $file, $category)
    {
        $name = $this->generateName($file->getFilename(), true) . '.' . $file->getFormat();
        $path = $this->generatePath($name, $category['path']);

        $success = $file->writeImage($path);

        return $success ? $name : false;
    }
}
