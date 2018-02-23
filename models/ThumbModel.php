<?php

namespace app\models;
use Gregwar\Image\Image;
use SplFileInfo;
use yii;
use yii\base\BaseObject;
use yii\helpers\Url;

/**
 * Модель для ресайза изображений.
 *
 * @package Delement\ComponentThumb\models
 */
class ThumbModel extends BaseObject
{

    /**
     * Директория, где хранятся миниатюры.
     *
     * @var string|null
     */
    public $mainUploadDir;

    /**
     * Путь к оригинальному изображению.
     *
     * @var string|null
     */
    public $origPath;

    /**
     * ширина в пикселях для ресайза.
     *
     * @var integer|null
     */
    public $width;

    /**
     * высота в пикселях для ресайза.
     *
     * @var integer|null
     */
    public $height;

    /**
     * Изображение по умолчанию.
     *
     * @var integer|null
     */
    public $defaultImage;

    /**
     * обрезать или нет.
     *
     * @var boolean
     */
    public $crop;

    /**
     * Получить ссылку к миниатюре.
     *
     * @return string
     */
    public function getThumb()
    {
        if ( ! is_file($this->origPath)) {
            return Url::base().$this->defaultImage;
        }
        $thumbPathName = $this->getPathToThumb().$this->getThumbName();
        if ( ! is_file($thumbPathName)) {
            $this->createThumb($thumbPathName);
        }

        $imgPath = str_replace(
            Yii::getAlias('@webroot'),
            '',
            $thumbPathName
        );

        return Url::base().$imgPath;
    }

    /**
     * Создать миниатюру для оригинала.
     *
     * @param string $thumbPathName путь к файлу с миниатюрой.
     *
     * @return void
     */
    protected function createThumb($thumbPathName)
    {
        $image = Image::open($this->origPath);
        $realWidth = $image->width();
        $realHeight = $image->height();
        if ($realWidth <= $this->width && $realHeight <= $this->height) {
            copy($this->origPath, $thumbPathName);

            return;
        }
        if ($this->crop) {
            $image = $this->clippingResize($image);
        } else {
            $image = $this->internalResize($image);
        }
        $image->save($thumbPathName);
    }

    /**
     * Метод создает миниатюру с использованием обрезания.
     *
     * @param Image $image информация по картинке.
     *
     * @return Image
     */
    protected function clippingResize(Image $image)
    {
        $x = $image->height() > $this->height ? ($image->height()
                - $this->height) / 2 : 0;
        $y = $image->width() > $this->width ? ($image->width() - $this->width)
            / 2 : 0;
        $image->zoomCrop($this->width, $this->height, $x, $y)->applyOperations();

        return $image;
    }

    /**
     * Метод, создает миниатюру без использования обрезания.
     *
     * @param Image $image информация по картинке.
     *
     * @return Image
     */
    protected function internalResize(Image $image)
    {
        $epsilon = 0.00001;
        if (abs(
                $image->width() / $this->width - $image->height()
                / $this->height
            )
            < $epsilon
        ) {
            $image->resize($this->width, $this->height)->applyOperations();
        } else {
            $k = $image->width() / $image->height();
            if ($this->width < ($k * $this->height)) {
                $newWidth = $this->width;
                $newHeight = $this->width / $k;
            } else {
                $newWidth = $k * $this->height;
                $newHeight = $this->height;
            }
            $image->cropResize($newWidth, $newHeight)->applyOperations();
        }

        return $image;
    }

    /**
     * Получить название для миниатюры.
     *
     * @return string
     */
    protected function getThumbName()
    {
        $splFileInfo = new SplFileInfo(basename($this->origPath));
        $fileName = str_replace(
            '.'.$splFileInfo->getExtension(),
            '',
            $splFileInfo
        );
        $isCrop = $this->crop ? 'crop.' : '';

        return $fileName.'.'.$this->width.'.'.$this->height.'.'.$isCrop
        .$splFileInfo->getExtension();
    }

    /**
     * Получить директорию где сохранится текущая миниатюра.
     *
     * @return string
     */
    protected function getPathToThumb()
    {
        $origDir = str_replace(Yii::getAlias('@app'), '', $this->origPath);
        $origDir = str_replace(basename($this->origPath), '', $origDir);

        return Yii::getAlias($this->mainUploadDir).$origDir;
    }
}