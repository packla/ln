<?php

namespace app\components;

use app\models\ThumbModel;
use Yii;
use yii\base\Component;

/**
 * Компонент для создания миниатюр.
 *
 * @package Delement\ComponentThumb\components
 */
class ThumbComponent extends Component
{

    /**
     * Директория, где хранятся миниатюры.
     *
     * @var string|null
     */
    protected $mainUploadDir;

    /**
     * Класса модели для ресайза.
     *
     * @var string|null
     */
    protected $thumbModelClass;

    /**
     * Изображение по умолчанию.
     *
     * @var integer|null
     */
    protected $defaultImage;

    /**
     * Получить объект для ресайза.
     *
     * @param string  $pathName путь к оригинальному файлу.
     * @param integer $width    ширина в пикселях для ресайза.
     * @param integer $height   высота в пикселях для ресайза.
     * @param boolean $crop     обрезать или нет.
     *
     * @return ThumbModel
     */
    public function getThumbModel($pathName, $width, $height, $crop)
    {
        return Yii::createObject(
            [
                'class'         => $this->thumbModelClass,
                'mainUploadDir' => $this->mainUploadDir,
                'origPath'      => $pathName,
                'width'         => $width,
                'height'        => $height,
                'defaultImage'  => $this->defaultImage,
                'crop'          => $crop,
            ]
        );
    }

    /***
     * Получить ссылку к миниатюре.
     *
     * @param string  $origPath путь к оригинальному файлу.
     * @param integer $width    ширина в пикселях для ресайза.
     * @param integer $height   высота в пикселях для ресайза.
     * @param boolean $crop     обрезать или нет.
     *
     * @return string
     */
    public function getThumb($origPath, $width, $height, $crop = false)
    {
        return $this->getThumbModel($origPath, $width, $height, $crop)
            ->getThumb();
    }

    /**
     * Установить значение атрибуту.
     *
     * @param string $mainUploadDir
     *
     * @return void
     */
    public function setMainUploadDir($mainUploadDir)
    {
        $this->mainUploadDir = $mainUploadDir;
    }

    /**
     * Установить значение атрибуту.
     *
     * @param string $thumbModelClass
     *
     * @return void
     */
    public function setThumbModelClass($thumbModelClass)
    {
        $this->thumbModelClass = $thumbModelClass;
    }

    /**
     * Установить значение атрибуту.
     *
     * @param string $defaultImage
     *
     * @return void
     */
    public function setDefaultImage($defaultImage)
    {
        $this->defaultImage = $defaultImage;
    }
}