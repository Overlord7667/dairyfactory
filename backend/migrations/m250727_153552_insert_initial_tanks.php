<?php

use yii\db\Migration;

/**
 * Handles inserting initial tanks into the tank table.
 */
class m250727_153552_insert_initial_tanks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->insert('tank', [
                'capacity' => 300,
                'current_volume' => 0,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удалим все цистерны, если понадобится откатить миграцию
        $this->delete('tank');
    }
}
