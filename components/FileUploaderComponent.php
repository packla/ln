<?php

namespace app\components;
use app\interfaces\UploadedFileInterface;
use app\models\UploaderModel;
use Yii;
use yii\base\Component;
/**
 * Компонент для загрузки файлов.
 *
 * @package Delement\ComponentUploader\components
 */
class FileUploaderComponent extends Component
{
    /**
     * Основная директория сохранения файлов.
     *
     * @var string|null
     */
    protected $mainUploadDir;
    /**
     * Название класса загрузки файлов.
     *
     * @var string|null
     */
    protected $uploaderModelClass;
    /**
     * Метод для загрузки файлов.
     *
     * @param array $config конфигурация для модели.
     *
     * @return boolean|UploadedFileInterface
     */
    public function upload(array $config)
    {
        $uploader = $this->getUploaderModel($config);
        return $uploader->upload();
    }
    /**
     * Получить полный путь к файлу.
     *
     * @param array $config конфигурация для модели.
     *
     * @return boolean|string
     */
    public function getFilePath(array $config)
    {
        $uploader = $this->getUploaderModel($config);
        return $uploader->getFilePath();
    }
    /**
     * Получить ссылку на файл.
     *
     * @param array $config конфигурация для модели.
     *
     * @return string
     * @deprecated
     */
    public function getFileUrl(array $config)
    {
        $uploader = $this->getUploaderModel($config);
        return $uploader->getFileUrl();
    }
    /**
     * Получить объект для загрузки файлов.
     *
     * @param array $config конфигурация для модели.
     *
     * @return UploaderModel
     */
    protected function getUploaderModel(array $config)
    {
        $config['class'] = $this->uploaderModelClass;
        if ( ! isset ($config['mainUploadDir'])) {
            $config['mainUploadDir'] = $this->mainUploadDir;
        }
        return Yii::createObject($config);
    }
    /**
     * Сеттер для атрибута.
     *
     * @param string $mainUploadDir значение.
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
     * @param mixed $uploaderModelClass
     *
     * @return void
     */
    public function setUploaderModelClass($uploaderModelClass)
    {
        $this->uploaderModelClass = $uploaderModelClass;
    }
}