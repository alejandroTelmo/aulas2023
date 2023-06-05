<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%aulas}}`.
 */
class m230603_024811_create_aulas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%aula}}', [
            'id' => $this->primaryKey(),
            'descripcion' => $this->string(),
            'ubicacion' => $this->string(50),
            'cant_pcs' => $this->integer(),
            'cant_proyectores' => $this->integer(),
            'es_climatizada' => $this->boolean(),
            'aforo' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%aula}}');
    }
}
