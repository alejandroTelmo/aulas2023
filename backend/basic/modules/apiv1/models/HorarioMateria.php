<?php

namespace app\modules\apiv1\models;

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
}