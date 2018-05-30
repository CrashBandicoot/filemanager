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

use CrashBandicoot\filemanager\validation\ValidationModel;


/**
 * Yii2 FileManager – PHP component for managing client files.
 *
 * To work with this extension you must add to your config in the components section next values:
 *    'fileManager' => [
 *        'class' => 'CrashBandicoot\filemanager\FileManager',
 *            'storage' => [
 *                's3' => [
 *                    'class' => 'CrashBandicoot\filemanager\storage\S3Storage',
 *                    'key' => 'YOUR S3 AUTH KEY',
 *                    'secret' => 'YOUR S3 SECRET',
 *                ],
 *            'local' => [
 *                'class' => 'CrashBandicoot\filemanager\storage\LocalStorage',
 *            ],
 *        ],
 *        'categories' => [
 *            'boat' => [
 *                'storage' => 'local',
 *                'path' => 'path_to_save_files',
 *                'webPath' => 'directory_available_from_web'
 *            ],
 *            'default' => [
 *                'storage' => 's3',
 *                'bucket' => 'YOUR_BUCKET_NAME',
 *                'region' => 'YOUR_REGION (for example "eu-central-1")',
 *                'path' => 'DIRECTORY_TO_SAVE_FILES',
 *                'webPath' => 's3 url to access your files from web (example "https://s3.YOUR_REGION.amazonaws.com/YOUR_BUCKET_NAME/DIRECTORY_TO_SAVE_FILES")'
 *            ],
 *        ],
 *    ],
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
        $this->setCategory($name);

        return $this;
    }

    public function upload($files, $validation = [])
    {
        $storage = $this->getStorage();

        $validationModel = \Yii::createObject([
            'class' => ValidationModel::className(),
            'config' => $validation,
            'filesArrName' => $files,
        ]);

        $uploads = $validationModel->check();

        if (is_array($uploads) && array_key_exists('files', $uploads)){
            return $uploads;
        }

        $uploads = is_array($uploads) ? $uploads : [$uploads];

        $result = [];
        foreach ($uploads as $upload) {
            $result[] = $storage->save($upload, $this->category);
        }

        return $result;
    }

    public function uploadByBlob($files)
    {
        $storage = $this->getStorage();

        $uploads = is_array($files) ? $files : [$files];

        $result = [];
        foreach ($uploads as $upload) {
            $result[] = $storage->saveBlob($upload, $this->category);
        }

        return $result;
    }

    public function get($name)
    {
        if ($this->category == null) {
            if (!array_key_exists('default', $this->categories)){
                throw new InvalidConfigException("Invalid configuration: category not set");
            }
            $this->category = $this->categories['default'];
        }

        return $this->category['webPath'] . substr($name, 0, 2) . '/' . $name;
    }

    /**
     * @return StorageInterface
     * @throws InvalidConfigException
     */
    public function getStorage()
    {
        if ($this->category == null) {
            if (!array_key_exists('default', $this->categories)){
                throw new InvalidConfigException("Invalid configuration: category not set");
            }
            $this->category = $this->categories['default'];
        }

        $storageName = $this->category['storage'];

        return StorageRegistry::getInstance($storageName, $this->storage[$storageName]);
    }

    public function setCategory($name)
    {
        if (isset($this->categories[$name])) {
            $this->category = $this->categories[$name];

            $storageName = $this->category['storage'];
            if (!isset($this->storage[$storageName])) {
                throw new InvalidConfigException("Invalid configuration: storage '{$storageName}' not exists. Check your config file");
            }

            if (!$this->category['path'] || is_dir($this->category['path'])) {
                throw new InvalidConfigException('Invalid configuration: category path empty or path not exists');
            }
        } else {
            $this->category = $this->categories['default'];
        }
    }
}
