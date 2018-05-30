<?php

namespace CrashBandicoot\filemanager\storage;

use Aws\S3\S3Client;
use Imagick;
use yii\web\UploadedFile;


class S3Storage extends BaseStorage implements StorageInterface
{

    protected $key;
    protected $secret;

    public function __construct($params = [])
    {
        $this->key = $params['key'];
        $this->secret = $params['secret'];
    }

    public function save(UploadedFile $file, $category)
    {
        $s3 = S3Client::factory([
            'signature' => 'v4',
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret
            ],
            'bucket' => $category['bucket'],
            'region' => $category['region'],
            'version' => 'latest',
        ]);

        $name = $this->generateName($file->getBaseName(), true).'.'.$file->getExtension();

        $upload = $s3->putObject(
            array(
                'Bucket'       => $category['bucket'],
                'Key'          => $category['path'] . substr($name, 0, 2) . '/' . $name,
                'SourceFile'   => $file->tempName,
                'ContentType'  => $file->type,
                'ACL'          => 'public-read',
            )
        );
        return $upload ? $name : null;
    }

    public function saveBlob(Imagick $file, $category)
    {
        $type = $file->getFormat();

        switch($type) {
            case 'bmp':
                $contentType = "image/bmp";
                break;
            case 'gif':
                $contentType = "image/gif";
                break;
            case 'jpg':
                $contentType = "image/jpg";
                break;
            case 'png':
                $contentType = "image/png";
                break;
            default :
                $contentType = "image/jpeg";
        }

        $s3 = S3Client::factory([
            'signature' => 'v4',
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret
            ],
            'bucket' => $category['bucket'],
            'region' => $category['region'],
            'version' => 'latest',
        ]);

        $name = $this->generateName($file->getFilename(), true).'.'.$type;

        $upload = $s3->putObject(
            array(
                'Bucket'       => $category['bucket'],
                'Key'          => $category['path'] . substr($name, 0, 2) . '/' . $name,
                'Body'         => $file->getImageBlob(),
                'ContentType'  => $contentType,
                'ACL'          => 'public-read'
            )
        );

        return $upload ? $name : null;
    }
}
