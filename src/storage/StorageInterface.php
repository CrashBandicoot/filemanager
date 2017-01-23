<?php

namespace CrashBandicoot\filemanager\storage;


interface StorageInterface
{
    /**
     * @param $file
     * @param $fileConfig
     * @return bool|string
     */
    public function save($file, $fileConfig);
}
