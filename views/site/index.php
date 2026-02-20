<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Book Catalog';
?>
<div class="site-index">

    <div class="py-5 mb-5 text-center rounded-3" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); color: #fff;">
        <div class="py-3">
            <h1 class="display-4 fw-bold mb-3">Book Catalog</h1>
            <p class="lead mb-4" style="color: #a8b2d8;">Browse books, discover authors, subscribe to updates via SMS</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <?= Html::a('Browse Books', ['/book/index'], ['class' => 'btn btn-primary btn-lg px-4']) ?>
                <?= Html::a('All Authors', ['/author/index'], ['class' => 'btn btn-outline-light btn-lg px-4']) ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 2.5rem;">📚</div>
                    <h5 class="card-title fw-bold">Book Catalog</h5>
                    <p class="card-text text-muted">Search books by title or year. View covers, descriptions, ISBN and authors.</p>
                    <?= Html::a('Open Catalog', ['/book/index'], ['class' => 'btn btn-sm btn-outline-primary mt-2']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 2.5rem;">✍️</div>
                    <h5 class="card-title fw-bold">Authors</h5>
                    <p class="card-text text-muted">Browse all authors and their works. Subscribe to an author and get SMS when a new book is added.</p>
                    <?= Html::a('All Authors', ['/author/index'], ['class' => 'btn btn-sm btn-outline-primary mt-2']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 2.5rem;">📊</div>
                    <h5 class="card-title fw-bold">Top-10 Report</h5>
                    <p class="card-text text-muted">See the top 10 most prolific authors for any selected year.</p>
                    <?= Html::a('View Report', ['/report/index'], ['class' => 'btn btn-sm btn-outline-primary mt-2']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="alert border-0 shadow-sm d-flex align-items-center gap-3" style="background:#f0f4ff;">
            <span style="font-size:1.5rem;">🔐</span>
            <div>
                <strong>Want to manage the catalog?</strong>
                <?= Html::a('Sign up', ['/site/signup']) ?> or <?= Html::a('log in', ['/site/login']) ?>
                to add, edit and delete books and authors.
            </div>
        </div>
    <?php else: ?>
        <div class="alert border-0 shadow-sm d-flex align-items-center gap-3" style="background:#f0fff4;">
            <span style="font-size:1.5rem;">👋</span>
            <div>
                Welcome back, <strong><?= Html::encode(Yii::$app->user->identity->getDisplayName()) ?></strong>!
                <?= Html::a('Add a new book', ['/book/create']) ?> or <?= Html::a('add an author', ['/author/create']) ?>.
            </div>
        </div>
    <?php endif ?>

</div>
