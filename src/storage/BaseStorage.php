<?php

namespace CrashBandicoot\filemanager\storage;


class BaseStorage
{
    protected function generateName($source, $hash = false)
    {
        $sourceParts = explode("/", $source);
        $filename = array_pop($sourceParts);

        if (!$hash) {
            return $filename;
        }

        $filenameParts = explode(".", $filename);
        $extension = array_pop($filenameParts);

        return sha1($filename . time()) . "." . $extension;
    }
}
