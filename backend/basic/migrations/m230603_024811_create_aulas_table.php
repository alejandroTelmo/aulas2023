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
            'descripcion' => $this->string(128)->notNull(),
            'ubicacion' => $this->string(128)->notNull(),
            'cant_pcs' => $this->integer()->defaultValue(0),
            'cant_proyectores' => $this->integer()->defaultValue(0),
            'es_climatizada' => $this->boolean()->defaultValue(false),
            'aforo' => $this->integer()->defaultValue(0),
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
