<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_aktivitas}}`.
 */
class m260523_073840_create_log_aktivitas_table extends Migration // <-- JANGAN UBAH NAMA CLASS INI, biarkan bawaan dari terminal lu
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Membuat tabel log_aktivitas
        $this->createTable('{{%log_aktivitas}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'tipe' => "ENUM('user', 'admin') NOT NULL",
            'deskripsi' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Opsional tapi penting: Bikin Index untuk user_id 
        // Biar kalau datanya udah ribuan, query penarikan log per user tetap secepat kilat!
        $this->createIndex(
            'idx-log_aktivitas-user_id',
            '{{%log_aktivitas}}',
            'user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Menghapus index dan tabel jika migration di-rollback (php yii migrate/down)
        $this->dropIndex('idx-log_aktivitas-user_id', '{{%log_aktivitas}}');
        $this->dropTable('{{%log_aktivitas}}');
    }
}