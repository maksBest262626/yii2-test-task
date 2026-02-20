<?php

namespace app\services;

use app\models\Author;
use app\models\Book;
use yii\db\Query;
use Yii;

class AuthorService
{
    /**
     * Удаляет автора и все книги, у которых он был единственным автором.
     * Книги с несколькими авторами остаются, только связь удаляется через CASCADE FK.
     */
    public function delete(Author $author): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $soloBookIds = (new Query())
                ->select('book_id')
                ->from('{{%book_author}}')
                ->where(['author_id' => $author->id])
                ->andWhere(['book_id' => (new Query())
                    ->select('book_id')
                    ->from('{{%book_author}}')
                    ->groupBy('book_id')
                    ->having('COUNT(*) = 1'),
                ])
                ->column();

            foreach (Book::findAll($soloBookIds) as $book) {
                if ($book->cover_image) {
                    $path = Book::getUploadDir() . $book->cover_image;
                    if (is_file($path)) {
                        unlink($path);
                    }
                }
                $book->delete();
            }

            $author->delete();
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}