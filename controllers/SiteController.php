<?php

namespace app\controllers;

use app\entities\DomainsAr;
use app\entities\SettingsAr;
use app\helpers\MainHelper;
use app\models\AuthModel;
use app\models\Installation;
use app\models\TableCreator;
use yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Installation::isDbConfigured()) {
            TableCreator::execute();
        }
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (! Installation::isDbConfigured()) {
            return $this->redirect('installation');
        }
        if (null === $settings = SettingsAr::getInstance()) {
            return $this->redirect('installation');
        }

        $domain   = MainHelper::getCurrentDomain();
        $settings = SettingsAr::getInstance();
        return $this->renderPartial('index', ['domain' => $domain, 'companyName' => $settings->companyName]);
    }

    public function actionInstallation()
    {
        if (Installation::isDbConfigured() && null !== $settings = SettingsAr::getInstance()) {
            return $this->redirect('/');
        }
        $install = new Installation();
        if (Yii::$app->request->post() && $install->install(Yii::$app->request->post())) {
            return $this->redirect('/');
        }
        return $this->render('installation', ['error' => current($install->getFirstErrors())]);
    }

    public function actionAdmin()
    {
        if (! AuthModel::checkAuth()) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        if (! Installation::isDbConfigured()) {
            return $this->redirect('installation');
        }
        if (null === $settings = SettingsAr::getInstance()) {
            return $this->redirect('installation');
        }
        $install  = new Installation();
        $settings = SettingsAr::getInstance();
        if (Yii::$app->request->post() && $install->update(Yii::$app->request->post())) {
            return $this->refresh();
        }
        $dataProvider = new yii\data\ActiveDataProvider([
            'query' => DomainsAr::find(),
            'sort'  => false,
        ]);
        $models       = $dataProvider->getModels();
        $model        = current($models);
        $attributes   = ArrayHelper::toArray(json_decode($model->data));
        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'attributes'   => array_keys($attributes),
            'models'       => $models,
            'companyName'  => $settings->companyName,
            'linksCount'   => $settings->linksCount,
        ]);
    }

    public function actionEditDomain($id)
    {
        if (null === $model = DomainsAr::findOne($id)) {
            throw new yii\web\NotFoundHttpException();
        }
        $attribute = Yii::$app->request->get('attribute');
        $value     = Yii::$app->request->get('value');
        if ('domain' === $attribute && DomainsAr::MAIN_DOMAIN === $model->domain) {
            return $this->asJson(['result' => false]);
        }
        if ($model->hasAttribute($attribute)) {
            $model->setAttributes([$attribute => $value]);
        } else {
            $data = $model->getArrayData();
            if (array_key_exists($attribute, $data)) {
                $data[$attribute] = $value;
                $model->data      = json_encode($data);
            }
        }
        return $this->asJson(['result' => $model->save()]);
    }
}
