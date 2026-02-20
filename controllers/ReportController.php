<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionIndex(): string
    {
        $year = (int)Yii::$app->request->get('year', 0);
        $results = [];

        if ($year > 0) {
            $results = Yii::$app->db->createCommand('
                SELECT a.full_name, COUNT(ba.book_id) as book_count
                FROM authors a
                JOIN book_author ba ON a.id = ba.author_id
                JOIN books b ON ba.book_id = b.id
                WHERE b.year = :year
                GROUP BY a.id, a.full_name
                ORDER BY book_count DESC
                LIMIT 10
            ')
            ->bindValue(':year', $year)
            ->queryAll();
        }

        return $this->render('index', [
            'year' => $year,
            'results' => $results,
        ]);
    }
}
