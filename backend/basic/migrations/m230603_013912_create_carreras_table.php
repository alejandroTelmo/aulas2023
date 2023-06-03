<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%carreras}}`.
 */
class m230603_013912_create_carreras_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carreras}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%carreras}}');
    }
}
