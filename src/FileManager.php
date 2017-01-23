<?php

namespace CrashBandicoot\filemanager;

use yii\base\{
    Object,
    InvalidConfigException
};
use CrashBandicoot\filemanager\storage\{
    StorageRegistry,
    StorageInterface
};


/**
 * Yii2 FileManager – PHP component for managing client files.
 *
 * @package CrashBandicoot/filemanager
 *
 * @method bool default() Default save method
 */
class FileManager extends Object
{

    protected $category = null;

    public $storage = [];

    public $categories = [];

    public function __construct($config = [])
    {
        if (isset($config['categories']) && !isset($config['categories']['default'])) {
            throw new InvalidConfigException('Default category should be set');
        }

        parent::__construct($config);
    }

    public function __call($name, $arguments)
    {
        if (isset($this->categories[$name])) {
            $this->category = $this->categories[$name];
            return $this;
        } else {
            return $this->default();
        }
    }

    public function upload($file)
    {
        if (!is_file($file) || !file_exists($file)) {
            throw new InvalidConfigException("Invalid configuration: file not set or not exists");
        }
        if ($this->category == null) {
            throw new InvalidConfigException("Invalid configuration: category not set");
        }

        $storageName = $this->category['storage'];
        if (!isset($this->storage[$storageName])) {
            throw new InvalidConfigException("Invalid configuration: storage '{$storageName}' not exists. Check your config file");
        }

        /** @var StorageInterface $storage */
        $storage = StorageRegistry::getInstance($storageName, $this->storage[$storageName]);
        return $storage->save($this->file, $this->category);
    }

    public function get($name)
    {
        return $this->category['webPath'] . $name;
    }
}
