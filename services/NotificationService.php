<?php

namespace app\services;

use app\models\Book;
use app\models\Subscription;
use Yii;

class NotificationService
{
    // TODO: change SmsPilotService to SmsServiceInterface AND do send only through queue for non stopping http. THIS REALIZATION ONLY FOR TEST TASK
    public function __construct(private readonly SmsPilotService $sms) {}

    public function notifyAboutNewBook(Book $book, array $authorIds): void
    {
        if (empty($authorIds)) {
            return;
        }

        $subscriptions = Subscription::find()
            ->where(['author_id' => $authorIds])
            ->with('author')
            ->all();

        foreach ($subscriptions as $subscription) {
            $message = "New book: \"{$book->title}\" ({$book->year}) by {$subscription->author->full_name}";
            $this->sms->send($subscription->phone, $message);
        }
    }
}