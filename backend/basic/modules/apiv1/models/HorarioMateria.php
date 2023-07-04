<?php

namespace app\modules\apiv1\models;

use yii\data\ActiveDataProvider;

class HorarioMateria extends \app\models\HorarioMateria
{
    public function getDataWithMateria($params){
        $sort = $params['sort'];
        $page = $params['page'];
        $perPage = $params['per-page'];
        $query = HorarioMateria::find();

        if (!stristr($sort, 'materia.nombre')){
        $query->getWithMateria()
              ->paginate($page,$perPage)
              ->orderBy($sort);
                                       
        } else {
        $query->getWithMateria()
              ->leftJoin('materia', 'horario_materia.id_materia = materia.id')
              ->paginate($page,$perPage)
              ->orderBy($sort);
        }
        
        return $query;
    }

    public function getDataBetweenDate($params){
        $id_materia = $params['id_materia'];
        $fh_desde = $params['fh_desde'];
        $fh_hasta = $params['fh_hasta'];

        $query = HorarioMateria::find();
        $query->where(['id_materia' => $id_materia, 'id_reserva' => null, 'clase_virtual' => false])
        ->andWhere(['>=', 'fh_desde', $fh_desde])
        ->andWhere(['<=', 'fh_hasta', $fh_hasta])
        ->asArray()
        ->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}