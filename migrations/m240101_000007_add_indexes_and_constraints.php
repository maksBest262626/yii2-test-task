<?php

use yii\db\Migration;

class m240101_000007_add_indexes_and_constraints extends Migration
{
    public function safeUp()
    {
        // Index for TOP-10 report which filters books by year
        $this->createIndex('idx_books_year', '{{%books}}', 'year');

        // Standalone index on author_id in book_author for reverse lookups
        // (composite PK (book_id, author_id) only accelerates lookups by book_id)
        $this->createIndex('idx_book_author_author_id', '{{%book_author}}', 'author_id');

        // Prevent the same phone from subscribing to the same author twice
        $this->createIndex(
            'uq_subscriptions_author_phone',
            '{{%subscriptions}}',
            ['author_id', 'phone'],
            true // unique
        );
    }

    public function safeDown()
    {
        $this->dropIndex('uq_subscriptions_author_phone', '{{%subscriptions}}');
        $this->dropIndex('idx_book_author_author_id', '{{%book_author}}');
        $this->dropIndex('idx_books_year', '{{%books}}');
    }
}