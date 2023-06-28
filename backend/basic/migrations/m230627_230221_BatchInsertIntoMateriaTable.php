<?php

use yii\db\Migration;

/**
 * Class m230627_230221_BatchInsertIntoMateriaTable
 */
class m230627_230221_BatchInsertIntoMateriaTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('materia', ['nombre','cant_alumnos','id_carrera','id_profesor'], [
            ['Frameworks e Interoperabilidad', '6', '1', '2'],
            ['Trabajo Final', '6', '1', '2'],
            ['Matematicas', '16', '1', '1'],
            ['Introducción a la Computación Arquitectura y Seguridad de Computadoras', '30', '2', '3'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('materia',"nombre = 'Frameworks e Interoperabilidad'");
        $this->delete('materia',"nombre = 'Trabajo Final'");
        $this->delete('materia',"nombre = 'Matematicas'");
        $this->delete('materia',"nombre = 'Introducción a la Computación Arquitectura y Seguridad de Computadoras'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_230221_BatchInsertIntoMateriaTable cannot be reverted.\n";

        return false;
    }
    */
}
