<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Установка';
?>
<div id="installationPage">
    <h3>Установка</h3>
    <p><small class="active">Основное</small> | <small>Шаблон</small> | <small>Поддомены</small></p>
    <form action="<?= Url::toRoute('installation'); ?>" method="post">
        <div class="main hidden">
            <div class="form-group">
                <label>Название компании *</label>
                <input type="text" name="name" required="required" />
            </div>
            <div class="form-group">
                <label>Адрес *</label>
                <input type="text" name="address" required="required" />
            </div>
            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" required="required" />
            </div>
            <div class="form-group">
                <label>E-mail *</label>
                <input type="email" name="email" required="required" />
            </div>
            <div class="form-group">
                <button type="submit">Далее</button>
            </div>
        </div>
        <div class="template">
            <div class="header"></div>
            <div class="body">
                <div class="block">
                    <div class="text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</div>
                    <img src="/images/default.jpg" />
                </div>
                <div class="block">
                    <div class="text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</div>
                    <img src="/images/default.jpg" />
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="footer"></div>
        </div>
    </form>
</div>
