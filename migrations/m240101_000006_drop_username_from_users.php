<?php

use yii\db\Migration;

class m240101_000006_drop_username_from_users extends Migration
{
    public function safeUp()
    {
        $this->dropIndex('username', '{{%users}}');
        $this->dropColumn('{{%users}}', 'username');
    }

    public function safeDown()
    {
        $this->addColumn('{{%users}}', 'username', $this->string(64)->notNull()->defaultValue(''));
        $this->createIndex('username', '{{%users}}', 'username', true);
    }
}