<?php

use yii\db\Migration;

class m240101_000003_create_books_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->smallInteger()->notNull(),
            'description' => $this->text()->null(),
            'isbn' => $this->string(20)->null(),
            'cover_image' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
