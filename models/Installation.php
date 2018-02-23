<?php

namespace app\models;

use app\entities\DomainsAr;
use app\entities\SettingsAr;
use PHPExcel_IOFactory;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Installation extends Model
{
    public $companyName;
    public $dbName;
    public $dbLogin;
    public $dbPassword;

    public function rules()
    {
        return [
            [
                ['companyName', 'dbName', 'dbLogin', 'dbPassword'],
                'required',
            ],

        ];
    }

    public function install($data)
    {
        $this->setAttributes($data);
        $files = UploadedFile::getInstancesByName('files');
        if (! $this->validate()) {
            return false;
        }
        if (! $this->setDbAccesses()) {
            return false;
        }

        $settings = new SettingsAr();
        $settings->setAttributes($this->getAttributes());

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($settings->save() && $this->saveDomains($files) && $this->uploadTemplates($files)) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
            return false;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function update($data)
    {
        $this->setAttributes($data);
        $this->subdomainsFile = UploadedFile::getInstanceByName('subdomainsFile');
        $settings             = SettingsAr::getInstance();
        $settings->setAttributes($this->getAttributes());

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $result = true;
            if (null !== $this->subdomainsFile) {
                $result = (bool)DomainsAr::deleteAll();
                $result = $result && $this->saveDomains();
            }
            if ($settings->save() && $result) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
            return false;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return bool
     */
    protected function saveDomains($files)
    {
        $subdomainFile = null;
        foreach ($files as $file) {
            $ext = $file->getExtension();
            if ('xls' === $ext || 'xlsx' === $ext) {
                $subdomainFile = $file;
                break;
            }
        }
        if (null === $subdomainFile) {
            $this->addError('subdomainFile', 'Данных для поддоменов не найдено');
        }

        // Поддомены
        $pExcel     = PHPExcel_IOFactory::load($subdomainFile->tempName);
        $attributes = [];
        foreach ($pExcel->getWorksheetIterator() as $page => $worksheet) {
            $data = $worksheet->toArray();
            if (isset($data[0]) && empty($attributes)) {
                $attributes = $data[0];
                if (! $this->validateAttributes($attributes)) {
                    return false;
                }
            }
            if (0 === $page && count($data) <= 1) {
                $this->addErrors(['Недостаточно данных для поддоменов']);
                return false;
            }
            foreach ($data as $index => $values) {
                if ($index === 0) {
                    continue;
                }
                if (0 === $page && 1 === $index) {
                    if (! $this->saveMainDomain($attributes, $values)) {
                        return false;
                    }
                    continue;
                }
                $this->saveDomain($attributes, $values);
            }
        }
        return true;
    }

    /**
     * Загрузка файлов шаблона.
     *
     * @param UploadedFile[] $files
     */
    protected function uploadTemplates($files)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $htmlExtensions  = ['html', 'php'];
        $imageDir        = Yii::getAlias('@webroot') . '/images/';
        $cssDir          = Yii::getAlias('@webroot') . '/css/';
        $jsDir           = Yii::getAlias('@webroot') . '/js/';
        $htmlFile        = Yii::getAlias('@app') . '/views/site/index.php';
        foreach ($files as $file) {
            $ext = $file->getExtension();
            if (ArrayHelper::isIn($ext, $imageExtensions)) {
                $file->saveAs($imageDir . $file->name);
            } elseif ('css' === $ext) {
                $file->saveAs($cssDir . $file->name);
            } elseif ('js' === $ext) {
                $file->saveAs($jsDir . $file->name);
            } elseif (ArrayHelper::isIn($ext, $htmlExtensions)) {
                $htmlContent = file_get_contents($file->tempName);
                $htmlContent = $this->normalizePath($htmlContent, '/<link.*href=["|\'](.*\.css)["|\'].*>/', '/css');
                $htmlContent = $this->normalizePath($htmlContent, '/<script.*src=["|\'](.*\.js)["|\'].*>/', '/js');
                $htmlContent = $this->normalizePath($htmlContent, '/<script.*src=["|\'](.*\.js)["|\'].*>/', '/js');
                $htmlContent = $this->normalizePath($htmlContent, '/<img.*src=["|\'](.*\.jpg)["|\'].*>/', '/images');
                $htmlContent = $this->normalizePath($htmlContent, '/<img.*src=["|\'](.*\.png)["|\'].*>/', '/images');
                $htmlContent = $this->normalizePath($htmlContent, '/<img.*src=["|\'](.*\.gif)["|\'].*>/', '/images');
                file_put_contents($htmlFile, $htmlContent);
            }
        }
        return true;
    }

    protected function setDbAccesses()
    {
        $str = $this->dbName . ':' . $this->dbLogin . ':' . $this->dbPassword;
        Yii::$app->getDb()->dsn .= $this->dbName;
        Yii::$app->getDb()->username = $this->dbLogin;
        Yii::$app->getDb()->password = $this->dbPassword;
        try {
            Yii::$app->getDb()->open();
        } catch (\yii\db\Exception $e) {
            $this->clearDbAccesses();
            $this->addError('dbName', 'Ошибка доступа к базе данных');
            return false;
        }
        file_put_contents(Yii::getAlias('@app/config/db_accesses.txt'), $str);
        return true;
    }

    protected function clearDbAccesses()
    {
        file_put_contents(Yii::getAlias('@app/config/db_accesses.txt'), '');
    }

    protected function normalizePath($content, $pattern, $path)
    {
        return preg_replace_callback($pattern, function ($matches) use ($path) {
            if (! isset($matches[1])) {
                return $matches[0];
            }
            if (false !== strpos($matches[1], 'http://') || false !== strpos($matches[1], 'https://')) {
                return $matches[0];
            }
            if (false !== $pos = strrpos($matches[1], '/')) {
                $fileName = substr($matches[1], $pos + 1);
            } else {
                $fileName = $matches[1];
            }
            return str_replace($matches[1], $path . '/' . $fileName, $matches[0]);
        }, $content);
    }

    protected function validateAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attribute === '%domain%') {
                return true;
            }
        }
        $this->addErrors(['Необходима колонка %domain% для поддоменов']);
        return false;
    }

    protected function saveDomain($attributes, $values)
    {
        $model = new DomainsAr();
        $data  = [];
        foreach ($attributes as $index => $attribute) {
            if ('%domain%' === $attribute) {
                $model->domain = $values[$index];
            } else {
                $data[$attribute] = $values[$index];
            }
        }
        $model->data = json_encode($data);
        return $model->save();
    }

    protected function saveMainDomain($attributes, $values)
    {
        $model = new DomainsAr();
        $data  = [];
        foreach ($attributes as $index => $attribute) {
            if ('%domain%' === $attribute) {
                $model->domain = DomainsAr::MAIN_DOMAIN;
            } else {
                $data[$attribute] = $values[$index];
            }
        }
        $model->data = json_encode($data);
        return $model->save();
    }

    public static function isDbConfigured()
    {
        $config = file_get_contents(Yii::getAlias('@app/config/db_accesses.txt'));
        return ! empty($config) && 3 === count(explode(':', $config));
    }
}
