<?php

namespace CrashBandicoot\filemanager\storage;

use yii\web\UploadedFile;

interface StorageInterface
{
    /**
     * @param $file
     * @param $fileConfig
     * @return bool|string
     */
    public function save(UploadedFile $file, $fileConfig);
}
