<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

            <?= $form->field($model, 'email')->input('email', ['autofocus' => true, 'placeholder' => 'your@email.com']) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end() ?>

            <p class="mt-3">Don't have an account? <?= Html::a('Sign up', ['/site/signup']) ?></p>
        </div>
    </div>
</div>