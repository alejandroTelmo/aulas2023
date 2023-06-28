<?php

use yii\db\Migration;

/**
 * Class m230627_224927_BatchInsertIntoProfesorTable
 */
class m230627_224927_BatchInsertIntoProfesorTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('profesor', ['nombre','apellido','mostrar'], [
            ['Julio', 'Colombo', 'Julio Colombo'],
            ['Cesar', 'Meloni',	'Cesar Meloni'],
            ['Nestor', 'Imberti', 'Nestor Fabian Imberti'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('profesor',"mostrar = 'Julio Colombo'");
        $this->delete('profesor',"mostrar = 'Cesar Meloni'");
        $this->delete('profesor',"mostrar = 'Nestor Fabian Imberti'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_224927_BatchInsertIntoProfesorTable cannot be reverted.\n";

        return false;
    }
    */
}
