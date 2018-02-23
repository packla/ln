<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

\app\assets\AdminAsset::register($this);
$this->title = 'Админ панель';
/** @var \app\entities\DomainsAr[] $models */
/** @var array $attributes */
/** @var \yii\data\ActiveDataProvider $dataProvider */
?>
<div class="wrapper">
    <div class="edit-data">
        <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>
        <table>
            <tr>
                <td>
                    <div class="form-group">
                        <label>Название компании *</label>
                        <?= Html::textInput('companyName', $companyName, ['required' => 'required']) ?>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label>Данные для поддоменов</label>
                        <?= Html::fileInput('subdomainsFile', null) ?>
                        <small>Формат параметров: %param%</small><br />
                        <small>Обязательный параметр - %domain%</small>
                    </div>
                </td>
            </tr>
        </table>
        <div class="form-group">
            <button type="submit">Обновить</button>
        </div>
        <?= Html::endForm(); ?>
    </div>
    <div class="line"></div>
    <div id="domainsGrid">
        <table>
            <thead>
                <tr>
                    <th>Домен</th>
                    <?php foreach ($attributes as $attribute): ?>
                        <th><?= $attribute ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($models as $model): ?>
                    <?php
                    $data = $model->getArrayData();
                    ?>
                    <tr>
                        <td>
                            <?php if ($model->isMain()): ?>
                                <small>Основной</small>
                            <?php else: ?>
                                <div class="form-group">
                                <?= Html::textInput('val', $model->domain, [
                                    'data-attribute' => 'domain',
                                    'data-url'       => Url::toRoute(['edit-domain', 'id' => $model->id]),
                                ]) ?>
                            </div>
                            <?php endif ?>
                        </td>
                        <?php foreach ($attributes as $attribute): ?>
                            <td>
                                <div class="form-group">
                                    <?= Html::textInput('val', $data[$attribute], [
                                        'data-attribute' => $attribute,
                                        'data-url'       => Url::toRoute(['edit-domain', 'id' => $model->id]),
                                    ]) ?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>
            <?php
            echo LinkPager::widget([
                'pagination'       => $dataProvider->getPagination(),
                'registerLinkTags' => true,
                'options'          => [
                    'class' => 'pagination text-center',
                ],
            ]);
            ?>
        </p>
    </div>
</div>