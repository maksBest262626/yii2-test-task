<?php

namespace app\services;

use app\models\Book;
use Yii;

class BookService
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    /**
     * Сохраняет новую книгу: файл, запись в БД, авторы, SMS-уведомления.
     * Выбрасывает исключение при ошибке — контроллер ловит и показывает flash.
     */
    public function create(Book $model, array $authorIds): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->saveImageFile($model);

            if (!$model->save(false)) {
                throw new \RuntimeException('Failed to save book.');
            }

            $model->saveAuthors($authorIds);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        // Уведомления — после коммита, вне транзакции
        $this->notificationService->notifyAboutNewBook($model, $authorIds);
    }

    /**
     * Обновляет существующую книгу.
     */
    public function update(Book $model, array $authorIds): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->saveImageFile($model);

            if (!$model->save(false)) {
                throw new \RuntimeException('Failed to save book.');
            }

            $model->saveAuthors($authorIds);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Удаляет книгу вместе с файлом обложки.
     */
    public function delete(Book $model): void
    {
        if ($model->cover_image) {
            $path = Book::getUploadDir() . $model->cover_image;
            if (is_file($path)) {
                unlink($path);
            }
        }

        $model->delete();
    }

    private function saveImageFile(Book $model): void
    {
        if (!$model->imageFile) {
            return;
        }

        $fileName = uniqid('book_') . '.' . $model->imageFile->extension;
        if (!$model->imageFile->saveAs(Book::getUploadDir() . $fileName)) {
            throw new \RuntimeException('Failed to save cover image.');
        }

        $model->cover_image = $fileName;
    }
}