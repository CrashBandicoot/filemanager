<?php

namespace CrashBandicoot\filemanager\storage;


interface StorageInterface
{
    public function save($file, $fileConfig):bool;
}
