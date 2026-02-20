<?php

use yii\db\Migration;

class m240101_000004_create_book_author_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY ([[book_id]], [[author_id]])',
        ]);

        $this->addForeignKey(
            'fk_book_author_book',
            '{{%book_author}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_book_author_author',
            '{{%book_author}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_book_author_book', '{{%book_author}}');
        $this->dropForeignKey('fk_book_author_author', '{{%book_author}}');
        $this->dropTable('{{%book_author}}');
    }
}
