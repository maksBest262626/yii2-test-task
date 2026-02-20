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
        $authorId = null;

        if ($form->load(Yii::$app->request->post())) {
            $authorId = $form->author_id;

            if ($form->validate()) {
                $subscription = new Subscription();
                $subscription->author_id = $form->author_id;
                $subscription->phone = $form->phone;

                if ($subscription->save()) {
                    Yii::$app->session->setFlash('success', 'You have subscribed successfully!');
                } else {
                    $errors = implode('; ', $subscription->getFirstErrors());
                    Yii::$app->session->setFlash('error', 'Failed to subscribe. ' . ($errors ?: 'Please try again.'));
                }
            } else {
                $errors = implode('; ', $form->getFirstErrors());
                Yii::$app->session->setFlash('error', 'Invalid data: ' . $errors);
            }
        }

        if ($authorId) {
            return $this->redirect(['/author/view', 'id' => $authorId]);
        }
        return $this->redirect(['/author/index']);
    }
}