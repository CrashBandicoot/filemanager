<?php

namespace CrashBandicoot\filemanager\storage;

use Yii;
use yii\base\InvalidConfigException;


class LocalStorage implements StorageInterface
{

    public function save($file, $category):bool
    {
        if (!$category['path'] || is_dir($category['path'])) {
            throw new InvalidConfigException('Invalid configuration: category path empty or path not exists');
        }

        return rename($file, Yii::getAlias($category['path']) . $this->generateName($file, true));
    }

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
