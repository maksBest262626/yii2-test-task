<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $year */
/** @var array $results */

$this->title = 'Top-10 Authors Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <form method="get" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Select Year</label>
            <input type="number" name="year" class="form-control" placeholder="e.g. 2023"
                   value="<?= $year > 0 ? $year : '' ?>" min="1000" max="9999">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Show</button>
        </div>
    </form>

    <?php if ($year > 0): ?>
        <h3>TOP-10 Authors for <?= (int)$year ?></h3>
        <?php if (empty($results)): ?>
            <p class="text-muted">No data found for <?= (int)$year ?>.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered" style="max-width: 500px;">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Author</th>
                        <th>Books</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Html::encode($row['full_name']) ?></td>
                            <td><strong><?= (int)$row['book_count'] ?></strong></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    <?php endif ?>
</div>
