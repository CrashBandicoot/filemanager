<?php

namespace CrashBandicoot\filemanager\storage;

use yii\web\UploadedFile;
use Imagick;

interface StorageInterface
{
    /**
     * @param $file
     * @param $fileConfig
     * @return bool|string
     */
    public function save(UploadedFile $file, $fileConfig);

    /**
     * @param $file
     * @param $fileConfig
     * @return bool|string
     */
    public function saveBlob(Imagick $file, $fileConfig);
}
