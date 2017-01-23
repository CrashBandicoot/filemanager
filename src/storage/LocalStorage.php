<?php

namespace CrashBandicoot\filemanager\storage;

use Yii;
use yii\base\InvalidConfigException;


class LocalStorage extends BaseStorage implements StorageInterface
{

    public function save($file, $category)
    {
        if (!$category['path'] || is_dir($category['path'])) {
            throw new InvalidConfigException('Invalid configuration: category path empty or path not exists');
        }
        $name = $this->generateName($file, true);
        $subPath = substr($name, 0, 2);
        if (!file_exists(Yii::getAlias($category['path']) . $subPath)) {
            mkdir(Yii::getAlias($category['path']) . $subPath, 02775, true);
            chmod(Yii::getAlias($category['path']) . $subPath, 02775);
        }
        $success = rename($file, Yii::getAlias($category['path']) . $subPath . '/' . $name);
        return $success ? $name : false;
    }
}
