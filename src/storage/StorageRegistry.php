<?php

namespace CrashBandicoot\filemanager\storage;

use yii\base\InvalidConfigException;


class StorageRegistry
{

    protected static $instances;

    /**
     * @param $name
     * @param $config
     * @return StorageInterface
     */
    public static function getInstance($name, $config)
    {
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = static::createStorageInstance($config);
        }

        return static::$instances[$name];
    }

    /**
     * @param $config
     * @return StorageInterface
     * @throws InvalidConfigException
     */
    private static function createStorageInstance($config)
    {
        $storageClass = $config['class'];
        if (!class_exists($storageClass)) {
            throw new InvalidConfigException("Invalid configuration: storage class '{$storageClass}' not exists");
        }

        $params = isset($config['params']) ? $config['params'] : [];

        $storage = new $storageClass($params);

        if (!($storage instanceof StorageInterface)) {
            throw new InvalidConfigException("Invalid configuration: storage class '{$storageClass}' should implement " . StorageInterface::class);
        }

        return $storage;
    }
}
