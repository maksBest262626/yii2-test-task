<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $search */
/** @var string|null $yearFilter */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Add Book', ['/book/create'], ['class' => 'btn btn-primary']) ?>
        <?php endif ?>
    </div>

    <form method="get" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by title..."
                   value="<?= Html::encode($search ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="number" name="year" class="form-control" placeholder="Year"
                   value="<?= Html::encode($yearFilter ?? '') ?>" min="1000" max="9999">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
        </div>
    </form>

    <?php if ($dataProvider->totalCount === 0): ?>
        <p class="text-muted">No books found.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($dataProvider->models as $book): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if ($book->coverUrl): ?>
                            <img src="<?= Html::encode($book->coverUrl) ?>"
                                 class="card-img-top" alt="Cover" style="max-height: 200px; object-fit: cover;">
                        <?php endif ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= Html::encode($book->title) ?></h5>
                            <p class="card-text text-muted"><?= (int)$book->year ?></p>
                            <?php if (!empty($book->authors)): ?>
                                <p class="card-text small">
                                    <?= Html::encode(implode(', ', array_column($book->authors, 'full_name'))) ?>
                                </p>
                            <?php endif ?>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('View', ['/book/view', 'id' => $book->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <?= Html::a('Edit', ['/book/update', 'id' => $book->id], ['class' => 'btn btn-sm btn-outline-secondary ms-1']) ?>
                                <?= Html::beginForm(['/book/delete', 'id' => $book->id], 'post', ['style' => 'display:inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-outline-danger ms-1',
                                    'onclick' => 'return confirm("Delete this book?")']) ?>
                                <?= Html::endForm() ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mt-4">
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
        </div>
    <?php endif ?>
</div>
