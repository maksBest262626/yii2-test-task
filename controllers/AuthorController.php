<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use app\models\Book;
use app\models\SubscriptionForm;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => Author::find()->with('books')->orderBy(['full_name' => SORT_ASC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        $author = $this->findModel($id);
        $books = $author->getBooks()->orderBy('year DESC, title ASC')->all();
        $subscriptionForm = new SubscriptionForm();
        $subscriptionForm->author_id = $id;

        return $this->render('view', [
            'model' => $author,
            'books' => $books,
            'subscriptionForm' => $subscriptionForm,
        ]);
    }

    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Author created successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Author updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id)
    {
        $author = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Find books where this author is the sole author
            $soloBookIds = (new Query())
                ->select('book_id')
                ->from('{{%book_author}}')
                ->where(['author_id' => $id])
                ->andWhere(['book_id' => (new Query())
                    ->select('book_id')
                    ->from('{{%book_author}}')
                    ->groupBy('book_id')
                    ->having('COUNT(*) = 1'),
                ])
                ->column();

            // Delete solo books and their cover images
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
            Yii::$app->session->setFlash('success', 'Author deleted.');
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Author delete failed: ' . $e->getMessage(), 'author');
            Yii::$app->session->setFlash('error', 'Could not delete author. Please try again.');
        }

        return $this->redirect(['index']);
    }

    private function findModel(int $id): Author
    {
        $model = Author::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested author does not exist.');
        }
        return $model;
    }
}
