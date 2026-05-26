<?php
use yii\db\Migration;

class m260520_063543_create_kategori_kerusakan_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%kategori_kerusakan}}', [
            'id' => $this->primaryKey(),
            'nama_kategori' => $this->string(50)->notNull(),
        ]);

        // Insert data awal
        $this->batchInsert('{{%kategori_kerusakan}}', ['nama_kategori'], [
            ['Ringan'], ['Sedang'], ['Berat'], ['Sangat Berat']
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%kategori_kerusakan}}');
    }
}