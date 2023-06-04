<?php

use yii\db\Migration;

/**
 * Class m230604_152237_create_reserva_aula
 */
class m230604_152237_create_reserva_aula extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reserva_aula}}', [
            'id' => $this->primaryKey(),
            'id_aula' => $this->integer(),
            'fh_desde' => $this->dateTime(),
            'fh_hasta' => $this->dateTime(),
            'observacion' => $this->string(256),
        ]);

        $this->addForeignKey('fk_reserva_aula_id_aula', 'reserva_aula', 'id_aula', 'aula', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_reserva_aula_id_aula',
            'reserva_aula'
        );

        $this->dropTable('{{%reserva_aula}}');
    }
}
