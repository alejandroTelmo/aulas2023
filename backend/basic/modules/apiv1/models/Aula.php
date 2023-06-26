<?php

namespace app\modules\apiv1\models;

use yii\data\ActiveDataProvider;

class Aula extends \app\models\Aula
{
    
    public function todos()
    {
        $query = Aula::find();

        $query->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}