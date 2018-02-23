<?php
/**
 * Created by PhpStorm.
 * User: Jah
 * Date: 27.11.2017
 * Time: 21:16
 */

namespace app\models;


use app\interfaces\UploadedFileInterface;
use Yii;
use yii\base\Model;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
/**
 * Модель для загрузки файлов.
 *
 * @package Delement\ComponentUploader\models
 */
class UploaderModel extends Model
{
    /**
     * Основная директория сохранения файлов.
     *
     * @var string|null
     */
    protected $mainUploadDir;
    /**
     * Объект загруженного в runtime файла.
     *
     * @var UploadedFile|null
     */
    protected $instance;
    /**
     * Идентификатор сущности для разделения на группы.
     *
     * @var integer|null
     */
    protected $entityId;
    /**
     * Тип сущности для разделения на группы.
     *
     * @var string|null
     */
    protected $entityType;
    /**
     * Название загруженного файла.
     *
     * @var string|null
     */
    protected $fileName;
    /**
     * Метод для загрузки файлов.
     *
     * @return boolean|UploadedFileInterface
     */
    public function upload()
    {
        $fileName = $this->generateUniqueName();
        $pathName = $this->getUploadDir()
            .$fileName;
        if ( ! $this->instance->saveAs($pathName)) {
            return false;
        }
        return new UploadedFileModel(
            [
                'fileName'  => $fileName,
                'baseName'  => $this->instance->baseName,
                'extension' => $this->instance->extension,
                'filePath'  => $pathName,
            ]
        );
    }
    /**
     * Получить путь к файлу.
     *
     * @return boolean|string
     */
    public function getFilePath()
    {
        $pathName = $this->getUploadDir()
            .$this->fileName;
        if (file_exists($pathName) && is_file($pathName)) {
            return $pathName;
        }
        return false;
    }
    /**
     * Получить ссылку на файл.
     *
     * @return string
     * @deprecated
     */
    public function getFileUrl()
    {
        $imgPath = str_replace(
            Yii::getAlias('@webroot'),
            '',
            $this->getUploadDir()
        );
        return Url::base().$imgPath.$this->fileName;
    }
    /**
     * генерация уникального имени для файла.
     *
     * @return string
     */
    protected function generateUniqueName()
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 8).'.'
        .$this->instance->extension;
    }
    /**
     * Сгенерировать директорию где будут храниться файлы для текущей сущности.
     *
     * @return string
     */
    protected function getUploadDir()
    {
        $group = str_pad(floor($this->entityId / 1000), 3, '0', STR_PAD_LEFT);
        $dir = Yii::getAlias($this->mainUploadDir).$this->entityType.'/'.$group
            .'/'.$this->entityId.'/';
        if ( ! is_dir($dir)) {
            BaseFileHelper::createDirectory($dir);
        }
        return $dir;
    }
    /**
     * Сеттер для атрибута.
     *
     * @param null|string $mainUploadDir
     *
     * @return void
     */
    public function setMainUploadDir($mainUploadDir)
    {
        $this->mainUploadDir = $mainUploadDir;
    }
    /**
     * Сеттер для атрибута.
     *
     * @param null|UploadedFile $instance
     *
     * @return void
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }
    /**
     * Сеттер для атрибута.
     *
     * @param integer $entityId
     *
     * @return void
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }
    /**
     * Сеттер для атрибута.
     *
     * @param string $entityType
     *
     * @return void
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }
    /**
     * Сеттер для атрибута.
     *
     * @param string $fileName
     *
     * @return void
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
}