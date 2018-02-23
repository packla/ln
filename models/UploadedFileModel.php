<?php
/**
 * Created by PhpStorm.
 * User: Jah
 * Date: 27.11.2017
 * Time: 21:17
 */

namespace app\models;

use app\interfaces\UploadedFileInterface;
use yii\base\BaseObject;

class UploadedFileModel extends BaseObject implements UploadedFileInterface
{
    protected $fileName;
    protected $baseName;
    protected $extension;
    protected $filePath;

    /**
     * Получить название сгенерированного файла.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param mixed $baseName
     */
    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Получить полный путь к файлу.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }
}