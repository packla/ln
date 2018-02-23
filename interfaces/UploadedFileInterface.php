<?php
/**
 * Created by PhpStorm.
 * User: Jah
 * Date: 27.11.2017
 * Time: 21:15
 */

namespace app\interfaces;

/**
 * Интерфейс для доступа к данным загруженного файла.
 *
 * @package Delement\ComponentUploader\interfaces
 */
interface UploadedFileInterface
{
    /**
     * Получить название сгенерированного файла.
     *
     * @return string
     */
    public function getFilename();
    /**
     * Получить расширение файла.
     *
     * @return string
     */
    public function getExtension();
    /**
     * Название файла с клиента.
     *
     * @return string
     */
    public function getBaseName();
    /**
     * Получить полный путь к файлу.
     *
     * @return string
     */
    public function getFilePath();
}