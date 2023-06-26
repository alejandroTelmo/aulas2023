<?php

use yii\db\Migration;

/**
 * Class m230604_152437_create_horario_materia
 */
class m230604_152437_create_horario_materia extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%horario_materia}}', [
            'id' => $this->primaryKey(),
            'id_materia' => $this->integer(),
            'id_reserva' => $this->integer(),
            'fh_desde' => $this->dateTime(),
            'fh_hasta' => $this->dateTime(),
            'clase_virtual' => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey('fk_horario_materia_id_materia', 'horario_materia', 'id_materia', 'materia', 'id');
        $this->addForeignKey('fk_horario_materia_id_reserva', 'horario_materia', 'id_reserva', 'reserva_aula', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_horario_materia_id_materia',
            'horario_materia'
        );

        $this->dropForeignKey(
            'fk_horario_materia_id_reserva',
            'horario_materia'
        );

        $this->dropTable('{{%horario_materia}}');
    }
}

