<?php

use yii\db\Migration;

class m250727_160000_create_milk_fill_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%milk_fill}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull(),
            'volume' => $this->integer()->notNull(),
            'tank_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Добавим внешний ключ к таблице tank (если нужно)
        $this->addForeignKey(
            'fk-milk_fill-tank_id',
            '{{%milk_fill}}',
            'tank_id',
            '{{%tank}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-milk_fill-tank_id', '{{%milk_fill}}');
        $this->dropTable('{{%milk_fill}}');
    }
}
