<?php

namespace CrashBandicoot\filemanager\storage;

use Yii;

class BaseStorage
{
    protected function generateName($source, $hash = false)
    {
        if (!$hash) {
            return $source;
        }

        return sha1($source . time());
    }

    protected function generatePath($fileName, $categoryPath)
    {
        $subPath = substr($fileName, 0, 2);

        $dir = Yii::getAlias($categoryPath) . $subPath;

        if (!file_exists($dir)) {
            mkdir($dir, 02775, true);
            chmod($dir, 02775);
        }

        return $dir . '/' . $fileName;
    }
}
