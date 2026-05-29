<?php
use yii\db\Migration;

class m260520_063607_create_laporan_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%laporan}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'kategori_id' => $this->integer()->notNull(),
            'judul' => $this->string()->notNull(),
            'deskripsi' => $this->text()->notNull(),
            'alamat_lokasi' => $this->text()->notNull(),
            'latitude' => $this->string(50),
            'longitude' => $this->string(50),
            'foto' => $this->string(), // Kita simpan nama file gambarnya
            'status' => $this->string(20)->notNull()->defaultValue('Menunggu'), // Menunggu, Proses, Selesai
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_priority' => $this->boolean()->defaultValue(0),
        ]);

        // Foreign Keys
        $this->addForeignKey('fk-laporan-user_id', '{{%laporan}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-laporan-kategori_id', '{{%laporan}}', 'kategori_id', '{{%kategori_kerusakan}}', 'id', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-laporan-kategori_id', '{{%laporan}}');
        $this->dropForeignKey('fk-laporan-user_id', '{{%laporan}}');
        $this->dropTable('{{%laporan}}');
    }
}