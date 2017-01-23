<?php

namespace CrashBandicoot\filemanager\storage;


class S3Storage implements StorageInterface
{

    public function __construct($params = [])
    {
    }

    public function save($file, $category):bool
    {
        return true;
    }
}
