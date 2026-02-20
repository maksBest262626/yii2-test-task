<?php

namespace app\controllers;

use Yii;
use app\models\Subscription;
use app\models\SubscriptionForm;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SubscriptionController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $form = new SubscriptionForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $subscription = new Subscription();
            $subscription->author_id = $form->author_id;
            $subscription->phone = $form->phone;
            if ($subscription->save()) {
                Yii::$app->session->setFlash('success', 'You have subscribed successfully!');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to subscribe. Please try again.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Invalid data. ' . implode(' ', array_merge(...array_values($form->getErrors()))));
        }

        $authorId = $form->author_id ?? Yii::$app->request->post('SubscriptionForm')['author_id'] ?? null;
        if ($authorId) {
            return $this->redirect(['/author/view', 'id' => $authorId]);
        }
        return $this->redirect(['/author/index']);
    }
}
