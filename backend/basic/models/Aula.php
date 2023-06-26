<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aula".
 *
 * @property int $id
 * @property string|null $descripcion
 * @property string|null $ubicacion
 * @property int $cant_pcs
 * @property int $cant_proyectores
 * @property bool $es_climatizada
 * @property int $aforo
 *
 * @property ReservaAula[] $reservaAulas
 */
class Aula extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aula';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'ubicacion'], 'required'],
            [['cant_pcs', 'cant_proyectores', 'aforo'], 'default', 'value' => 0],
            [['cant_pcs', 'cant_proyectores', 'aforo'], 'integer'],
            [['es_climatizada'], 'default', 'value' => false],
            [['es_climatizada'], 'boolean'],
            [['descripcion', 'ubicacion'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'ubicacion' => 'Ubicacion',
            'cant_pcs' => 'Cant Pcs',
            'cant_proyectores' => 'Cant Proyectores',
            'es_climatizada' => 'Es Climatizada',
            'aforo' => 'Aforo',
        ];
    }

    /**
     * Gets query for [[ReservaAulas]].
     *
     * @return \yii\db\ActiveQuery|ReservaAulaQuery
     */
    public function getReservaAulas()
    {
        return $this->hasMany(ReservaAula::class, ['id_aula' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return AulaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AulaQuery(get_called_class());
    }
}
