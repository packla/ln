<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

\app\assets\InstallAsset::register($this);
$this->title = 'Установка';
?>
<div id="installationPage">
    <h3>Установка</h3>
    <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>
    <?php if (null !== $error): ?>
        <div class="form-group error">
            <small><?= $error ?></small>
        </div>
    <?php endif ?>
    <div class="form-group">
        <label>Название компании *</label>
        <?= Html::textInput('companyName', null, ['required' => 'required']) ?>
    </div>
    <div class="line"></div>
    <div class="form-group db-group">
        <label>Доступы к базе данных: *</label>
        <div class="item"><?= Html::textInput('dbName', null, ['required' => 'required', 'placeholder' => 'Имя базы']) ?></div>
        <div class="item"><?= Html::textInput('dbLogin', null, ['required' => 'required', 'placeholder' => 'Логин']) ?></div>
        <div class="item"><?= Html::textInput('dbPassword', null, ['required' => 'required', 'placeholder' => 'Пароль']) ?></div>
        <div class="clearfix"></div>
    </div>
    <div class="line"></div>
    <div class="form-group template-group">
        <label>Файлы [данные для поддомена, css, js, изображения]</label>
        <div class="file_upload">
            <button>Выбрать файлы</button>
            <?= Html::fileInput('files[]', null, ['multiple' => true, 'required' => 'required']) ?>
        </div>
        <small>Формат параметров для поддоменов: %param%</small><br />
        <small>Обязательный параметр - %domain%</small>
    </div>
    <div class="line"></div>
    <div class="form-group">
        <label>Количество ссылок на поддомены: *</label>
        <?= Html::textInput('linksCount', 2, ['type' => 'number']) ?>
    </div>
    <div class="line"></div>
    <div class="form-group">
        <button type="submit">Генерировать</button>
    </div>
    <?= Html::endForm(); ?>
</div>
