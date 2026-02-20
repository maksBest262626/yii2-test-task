<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Author;
use app\models\Subscription;
use app\services\SmsPilotService;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $query = Book::find()->with('authors');

        $search = Yii::$app->request->get('search');
        $yearFilter = Yii::$app->request->get('year');

        if (!empty($search)) {
            $query->andWhere(['like', 'title', $search]);
        }
        if (!empty($yearFilter)) {
            $query->andWhere(['year' => (int)$yearFilter]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'search' => $search,
            'yearFilter' => $yearFilter,
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $authors = Author::find()->orderBy('full_name')->all();

        if (empty($authors)) {
            Yii::$app->session->setFlash('danger', 'You cannot add a book until at least one author exists. <a href="' . \yii\helpers\Url::to(['/author/create']) . '">Create an author</a> first.');
            return $this->redirect(['/book/index']);
        }

        $model = new Book();

        if ($model->load(Yii::$app->request->post())) {
            $authorIds = array_filter(array_map('intval', (array)$model->author_ids));

            if (empty($authorIds)) {
                Yii::$app->session->setFlash('danger', 'Please select at least one author.');
                return $this->render('create', ['model' => $model, 'authors' => $authors]);
            }

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->imageFile) {
                        $uploadPath = Yii::getAlias('@webroot/uploads/books/');
                        $fileName = uniqid('book_') . '.' . $model->imageFile->extension;
                        if (!$model->imageFile->saveAs($uploadPath . $fileName)) {
                            throw new \RuntimeException('Failed to save cover image.');
                        }
                        $model->cover_image = $fileName;
                    }

                    if (!$model->save(false)) {
                        throw new \RuntimeException('Failed to save book.');
                    }

                    $model->saveAuthors($authorIds);
                    $transaction->commit();

                    $this->notifySubscribers($model, $authorIds);
                    Yii::$app->session->setFlash('success', 'Book created successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), 'book');
                    Yii::$app->session->setFlash('danger', 'Failed to create book. Please try again.');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $model->author_ids = $model->authorIds;
        $authors = Author::find()->orderBy('full_name')->all();

        if ($model->load(Yii::$app->request->post())) {
            $authorIds = array_filter(array_map('intval', (array)$model->author_ids));

            if (empty($authorIds)) {
                Yii::$app->session->setFlash('danger', 'Please select at least one author.');
                return $this->render('update', ['model' => $model, 'authors' => $authors]);
            }

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->imageFile) {
                        $uploadPath = Yii::getAlias('@webroot/uploads/books/');
                        $fileName = uniqid('book_') . '.' . $model->imageFile->extension;
                        if (!$model->imageFile->saveAs($uploadPath . $fileName)) {
                            throw new \RuntimeException('Failed to save cover image.');
                        }
                        $model->cover_image = $fileName;
                    }

                    if (!$model->save(false)) {
                        throw new \RuntimeException('Failed to save book.');
                    }

                    $model->saveAuthors($authorIds);
                    $transaction->commit();

                    Yii::$app->session->setFlash('success', 'Book updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), 'book');
                    Yii::$app->session->setFlash('danger', 'Failed to update book. Please try again.');
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);

        if ($model->cover_image) {
            $path = Yii::getAlias('@webroot/uploads/books/') . $model->cover_image;
            if (is_file($path)) {
                unlink($path);
            }
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Book deleted.');
        return $this->redirect(['index']);
    }

    private function findModel(int $id): Book
    {
        $model = Book::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested book does not exist.');
        }
        return $model;
    }

    private function notifySubscribers(Book $book, array $authorIds): void
    {
        if (empty($authorIds)) {
            return;
        }

        $subscriptions = Subscription::find()
            ->where(['author_id' => $authorIds])
            ->with('author')
            ->all();

        if (empty($subscriptions)) {
            return;
        }

        // TODO: make smsServiceInterface AND make this send through queue
        $sms = new SmsPilotService();
        foreach ($subscriptions as $subscription) {
            $message = "New book: \"{$book->title}\" ({$book->year}) by {$subscription->author->full_name}";
            $sms->send($subscription->phone, $message);
        }
    }
}
