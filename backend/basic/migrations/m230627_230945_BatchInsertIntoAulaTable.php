<?php

use yii\db\Migration;

/**
 * Class m230627_230945_BatchInsertIntoAulaTable
 */
class m230627_230945_BatchInsertIntoAulaTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('aula', ['descripcion','ubicacion','cant_pcs','cant_proyectores','es_climatizada','aforo'], [
            ['Aula Principal', 'Edificio 1', '15', '5', true, '30'],
            ['Aula Secundaria', 'Edificio 1', '10', '2', false, '10'],
            ['Aula Magna', 'Edificio 2', '40', '6', true, '40'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('aula',"descripcion = 'Aula Principal'");
        $this->delete('aula',"descripcion = 'Aula Secundaria'");
        $this->delete('aula',"descripcion = 'Aula Magna'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_230945_BatchInsertIntoAulaTable cannot be reverted.\n";

        return false;
    }
    */
}
