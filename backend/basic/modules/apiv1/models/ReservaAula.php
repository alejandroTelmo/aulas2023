<?php

namespace app\modules\apiv1\models;

use yii\data\ActiveDataProvider;

class ReservaAula extends \app\models\ReservaAula
{

    public function calendario($params)
    {
        $query = ReservaAula::find();

        if ($params['id_aula'] != 'null'){
            $query ->select("reserva_aula.id, reserva_aula.fh_desde as start, reserva_aula.fh_hasta as end, reserva_aula.observacion, materia.nombre")
            ->leftJoin('horario_materia', 'horario_materia.id_reserva = reserva_aula.id')
            ->leftJoin('materia', 'materia.id = horario_materia.id_materia')
            ->where(['>=', 'reserva_aula.fh_desde', $params['fh_desde']])
            ->andWhere(['<=', 'reserva_aula.fh_hasta', $params['fh_hasta']])
            ->andWhere(['=', 'reserva_aula.id_aula', $params['id_aula']])
            ->asArray();
        }else{
            $query ->select("reserva_aula.id, reserva_aula.fh_desde as start, reserva_aula.fh_hasta as end, reserva_aula.observacion, materia.nombre")
            ->leftJoin('horario_materia', 'horario_materia.id_reserva = reserva_aula.id')
            ->leftJoin('materia', 'materia.id = horario_materia.id_materia')
            ->where(['>=', 'reserva_aula.fh_desde', $params['fh_desde']])
            ->andWhere(['<=', 'reserva_aula.fh_hasta', $params['fh_hasta']])
            ->asArray();
        }       
        $query->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
