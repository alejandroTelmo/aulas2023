<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profesor".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $apellido
 * @property bool|null $mostrar_nombre
 *
 * @property Materia[] $materias
 */
class Profesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mostrar_nombre'], 'boolean'],
            [['nombre'], 'string', 'max' => 60],
            [['apellido'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'mostrar_nombre' => 'Mostrar Nombre',
        ];
    }

    /**
     * Gets query for [[Materias]].
     *
     * @return \yii\db\ActiveQuery|MateriaQuery
     */
    public function getMaterias()
    {
        return $this->hasMany(Materia::class, ['id_profesor' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProfesorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfesorQuery(get_called_class());
    }
}
