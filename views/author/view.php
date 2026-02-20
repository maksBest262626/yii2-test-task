<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var app\models\Book[] $books */
/** @var app\models\SubscriptionForm $subscriptionForm */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['/author/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div>
                <?= Html::a('Edit', ['/author/update', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
                <?= Html::beginForm(['/author/delete', 'id' => $model->id], 'post', ['style' => 'display:inline']) ?>
                <?= Html::submitButton('Delete', ['class' => 'btn btn-danger ms-1',
                    'onclick' => 'return confirm("Delete this author? Books where this is the only author will also be deleted.")']) ?>
                <?= Html::endForm() ?>
            </div>
        <?php endif ?>
    </div>

    <h3>Books</h3>
    <?php if (empty($books)): ?>
        <p class="text-muted">No books yet.</p>
    <?php else: ?>
        <ul class="list-group mb-4">
            <?php foreach ($books as $book): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= Html::a(Html::encode($book->title), ['/book/view', 'id' => $book->id]) ?>
                    <span class="badge bg-secondary"><?= (int)$book->year ?></span>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="card mt-4" style="max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title">Subscribe to new books</h5>
                <p class="card-text text-muted small">Get SMS notifications when new books by this author are added.</p>
                <?php $form = ActiveForm::begin(['action' => ['/subscription/create'], 'method' => 'post']) ?>
                <?= Html::hiddenInput('SubscriptionForm[author_id]', $model->id) ?>
                <?= $form->field($subscriptionForm, 'phone')->textInput(['placeholder' => '+79001234567'])->label('Your Phone') ?>
                <?= Html::submitButton('Subscribe', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    <?php endif ?>

    <p class="mt-4"><?= Html::a('&laquo; Back to Authors', ['/author/index'], ['class' => 'btn btn-outline-secondary']) ?></p>
</div>
