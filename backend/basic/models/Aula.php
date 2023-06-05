<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aula".
 *
 * @property int $id
 * @property string|null $descripcion
 * @property string|null $ubicacion
 * @property int|null $cant_pcs
 * @property int|null $cant_proyectores
 * @property bool|null $es_climatizada
 * @property int|null $aforo
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
            [['cant_pcs', 'cant_proyectores', 'aforo'], 'default', 'value' => null],
            [['cant_pcs', 'cant_proyectores', 'aforo'], 'integer'],
            [['es_climatizada'], 'boolean'],
            [['descripcion'], 'string', 'max' => 255],
            [['ubicacion'], 'string', 'max' => 50],
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
