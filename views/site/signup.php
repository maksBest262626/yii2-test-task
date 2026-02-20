<?php

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(['id' => 'signup-form']) ?>

            <?= $form->field($model, 'email')->input('email', ['autofocus' => true, 'placeholder' => 'your@email.com']) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Min. 6 characters']) ?>
            <?= $form->field($model, 'passwordRepeat')->passwordInput(['placeholder' => 'Repeat password']) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end() ?>

            <p class="mt-3">Already have an account? <?= Html::a('Log in', ['/site/login']) ?></p>
        </div>
    </div>
</div>