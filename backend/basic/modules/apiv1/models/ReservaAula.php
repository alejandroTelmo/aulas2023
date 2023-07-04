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

    public function saveBatchReservaAula($params){
        $transaction = ReservaAula::getDb()->beginTransaction();
        $ids_reservaAula = [];
        try {
            foreach ($params as $value) {
                $reservaAula = new ReservaAula();
                $reservaAula->id_aula = $value['id_aula'];
                $reservaAula->fh_desde = $value['fh_desde'];
                $reservaAula->fh_hasta = $value['fh_hasta'];
                $reservaAula->save();
                array_push($ids_reservaAula,$reservaAula->id);
                $horarioMateria = HorarioMateria::findOne(['id'=> $value['id_horarioMateria']]);
                $horarioMateria->id_reserva = $reservaAula->id;
                $horarioMateria->save();
            }
            $transaction->commit();
            return new ActiveDataProvider([
                'query' => ReservaAula::find()->where(['id'=>$ids_reservaAula]),
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
