<?php
/**
 * Created by PhpStorm.
 * User: carnat
 * Date: 20.02.17
 * Time: 19:02
 */

namespace CrashBandicoot\filemanager\validation;

use yii\base\Model;
use yii\web\UploadedFile;

class ValidationModel extends Model
{

    public $filesArrName;
    public $config;
    /**
     * @var UploadedFile
     */
    public $files;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['files'], 'file',
                'skipOnEmpty' => $this->config['skipOnEmpty'] ?? false,
                'extensions' => $this->config['extensions'] ?? 'png, jpg',
                'maxFiles' => $this->config['maxFiles'] ?? 1,
                'maxSize' => $this->config['maxSize'] ?? null,
            ],
        ];
    }

    public function check()
    {
        $this->files = UploadedFile::getInstancesByName($this->filesArrName);

        if (!isset($this->config['maxFiles']) || $this->config['maxFiles'] === 1){
            $this->files = array_shift($this->files);
        }

        if (!$this->validate()){
            return $this->getErrors();
        }

        return $this->files;
    }
}