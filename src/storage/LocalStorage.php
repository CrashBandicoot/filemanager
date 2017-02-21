<?php

namespace CrashBandicoot\filemanager\storage;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;


class LocalStorage extends BaseStorage implements StorageInterface
{

    public function save(UploadedFile $file, $category)
    {
        if (!$category['path'] || is_dir($category['path'])) {
            throw new InvalidConfigException('Invalid configuration: category path empty or path not exists');
        }
        $name = $this->generateName($file->getBaseName(), true) . '.' . $file->getExtension();
        $subPath = substr($name, 0, 2);
        if (!file_exists(Yii::getAlias($category['path']) . $subPath)) {
            mkdir(Yii::getAlias($category['path']) . $subPath, 02775, true);
            chmod(Yii::getAlias($category['path']) . $subPath, 02775);
        }
        $success = $file->saveAs(Yii::getAlias($category['path']) . $subPath . '/' . $name);
        return $success ? $name : false;
    }
}
