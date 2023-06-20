<?php

namespace app\modules\apiv1\models;

use yii\data\ActiveDataProvider;

class Materia extends \app\models\Materia
{
    
    public function buscar($params)
    {
        $query = Materia::find();

        $query->andFilterWhere(['ilike', 'nombre', $params['nombre']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}