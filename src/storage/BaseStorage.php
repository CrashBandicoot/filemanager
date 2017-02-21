<?php

namespace CrashBandicoot\filemanager\storage;


class BaseStorage
{
    protected function generateName($source, $hash = false)
    {
        if (!$hash) {
            return $source;
        }

        return sha1($source . time());
    }
}
