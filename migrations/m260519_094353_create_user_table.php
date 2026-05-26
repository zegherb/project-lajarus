<?php

use yii\db\Migration;

class m260519_094353_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(20)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'role' => $this->string(10)->notNull()->defaultValue('user'), // user atau admin
            'status' => $this->smallInteger()->notNull()->defaultValue(10), // 10 = Aktif
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}