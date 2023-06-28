<?php

use yii\db\Migration;

/**
 * Class m230627_235305_BatchInsertIntoReserva_AulaTable
 */
class m230627_235305_BatchInsertIntoReserva_AulaTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('reserva_aula', ['id_aula', 'fh_desde', 'fh_hasta', 'observacion'], [
            ['1','2023-06-02 16:00:00','2023-06-02 18:00:00',null],
            ['1','2023-06-09 16:00:00','2023-06-09 18:00:00',null],
            ['1','2023-06-16 16:00:00','2023-06-16 18:00:00',null],
            ['1','2023-06-23 16:00:00','2023-06-23 18:00:00',null],
            ['1','2023-06-30 16:00:00','2023-06-30 18:00:00',null],
            ['1','2023-07-07 16:00:00','2023-07-07 18:00:00',null],
            ['1','2023-07-14 16:00:00','2023-07-14 18:00:00',null],
            ['1','2023-07-21 16:00:00','2023-07-21 18:00:00',null],
            ['1','2023-07-28 16:00:00','2023-07-28 18:00:00',null],
            ['3','2023-06-02 16:00:00','2023-06-02 18:00:00','Charla Seguridad Web'],
            ['3','2023-06-09 16:00:00','2023-06-09 18:00:00','Seminario de Redes'],
            ['3','2023-06-16 16:00:00','2023-06-16 18:00:00','FLISOL'],
            ['3','2023-06-23 16:00:00','2023-06-23 18:00:00','Seminario Seguridad Informatica'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230627_235305_BatchInsertIntoReserva_AulaTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_235305_BatchInsertIntoReserva_AulaTable cannot be reverted.\n";

        return false;
    }
    */
}
