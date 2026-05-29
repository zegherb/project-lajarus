<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%komentar_laporan}}`.
 */
class m260529_000000_create_komentar_laporan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%komentar_laporan}}', [
            'id' => $this->primaryKey(),
            'laporan_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'isi_komentar' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Index
        $this->createIndex(
            'idx-komentar_laporan-laporan_id',
            'komentar_laporan',
            'laporan_id'
        );

        $this->createIndex(
            'idx-komentar_laporan-user_id',
            'komentar_laporan',
            'user_id'
        );

        // Foreign Key ke laporan
        $this->addForeignKey(
            'fk-komentar_laporan-laporan_id',
            'komentar_laporan',
            'laporan_id',
            'laporan',
            'id',
            'CASCADE'
        );

        // Foreign Key ke user
        $this->addForeignKey(
            'fk-komentar_laporan-user_id',
            'komentar_laporan',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-komentar_laporan-laporan_id',
            'komentar_laporan'
        );

        $this->dropForeignKey(
            'fk-komentar_laporan-user_id',
            'komentar_laporan'
        );

        $this->dropIndex(
            'idx-komentar_laporan-laporan_id',
            'komentar_laporan'
        );

        $this->dropIndex(
            'idx-komentar_laporan-user_id',
            'komentar_laporan'
        );

        $this->dropTable('{{%komentar_laporan}}');
    }
}
