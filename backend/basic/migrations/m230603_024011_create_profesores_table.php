<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profesores}}`.
 */
class m230603_024011_create_profesores_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profesores}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(60),
            'apellido' => $this->string(50),
            'mostrar_nombre' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%profesores}}');
    }
}
