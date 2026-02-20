<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p><?= Html::a('Add Author', ['/author/create'], ['class' => 'btn btn-primary mb-3']) ?></p>
    <?php endif ?>

    <?php if ($dataProvider->totalCount === 0): ?>
        <p class="text-muted">No authors found.</p>
    <?php else: ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Books</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->models as $author): ?>
                    <tr>
                        <td><?= Html::a(Html::encode($author->full_name), ['/author/view', 'id' => $author->id]) ?></td>
                        <td><?= count($author->books) ?></td>
                        <td>
                            <?= Html::a('View', ['/author/view', 'id' => $author->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <?= Html::a('Edit', ['/author/update', 'id' => $author->id], ['class' => 'btn btn-sm btn-outline-secondary ms-1']) ?>
                                <?= Html::beginForm(['/author/delete', 'id' => $author->id], 'post', ['style' => 'display:inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-outline-danger ms-1',
                                    'onclick' => 'return confirm("Delete this author and all their books?")']) ?>
                                <?= Html::endForm() ?>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
    <?php endif ?>
</div>
