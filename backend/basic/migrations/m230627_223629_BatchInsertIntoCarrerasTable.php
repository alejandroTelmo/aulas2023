<?php

use yii\db\Migration;

/**
 * Class m230627_223629_BatchInsertIntoCarrerasTable
 */
class m230627_223629_BatchInsertIntoCarrerasTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('carrera', ['nombre'], [
            ['TECNICATURA EN DESARROLLO WEB'],
            ['TECNICATURA UNIVERSITARIA EN ADMINISTRACIÓN DE SISTEMAS Y SOFTWARE LIBRE'],
            ['LICENCIATURA EN ENFERMERIA'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('carrera',"nombre='TECNICATURA EN DESARROLLO WEB'");
        $this->delete('carrera',"nombre='TECNICATURA UNIVERSITARIA EN ADMINISTRACIÓN DE SISTEMAS Y SOFTWARE LIBRE'");
        $this->delete('carrera',"nombre='LICENCIATURA EN ENFERMERIA'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_223629_BatchInsertIntoCarrerasTable cannot be reverted.\n";

        return false;
    }
    */
}
