<?php

use yii\db\Migration;

/**
 * Class m230627_232404_BatchInsertIntoHorarioMateriaTable
 */
class m230627_242405_BatchInsertIntoHorario_MateriaTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('horario_materia', ['id_materia', 'id_reserva', 'fh_desde', 'fh_hasta', 'clase_virtual'], [
            ['1','1','2023-06-02 16:00:00','2023-06-02 18:00:00', false],
            ['1','2','2023-06-09 16:00:00','2023-06-09 18:00:00', false],
            ['1','3','2023-06-16 16:00:00','2023-06-16 18:00:00', false],
            ['1','4','2023-06-23 16:00:00','2023-06-23 18:00:00', false],
            ['1','5','2023-06-30 16:00:00','2023-06-30 18:00:00', false],
            ['1','6','2023-07-07 16:00:00','2023-07-07 18:00:00', false],
            ['1','7','2023-07-14 16:00:00','2023-07-14 18:00:00', false],
            ['1','8','2023-07-21 16:00:00','2023-07-21 18:00:00', false],
            ['1','9','2023-07-28 16:00:00','2023-07-28 18:00:00', false],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('horario_materia','id_reserva = 1');
        $this->delete('horario_materia','id_reserva = 2');
        $this->delete('horario_materia','id_reserva = 3');
        $this->delete('horario_materia','id_reserva = 4');
        $this->delete('horario_materia','id_reserva = 5');
        $this->delete('horario_materia','id_reserva = 6');
        $this->delete('horario_materia','id_reserva = 7');
        $this->delete('horario_materia','id_reserva = 8');
        $this->delete('horario_materia','id_reserva = 9');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_232404_BatchInsertIntoHorarioMateriaTable cannot be reverted.\n";

        return false;
    }
    */
}
