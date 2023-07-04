<?php

namespace app\modules\apiv1\controllers;

use app\modules\apiv1\models\HorarioMateria;
use app\modules\apiv1\models\ReservaAula;
use Yii;


/**
 * Default controller for the `apiv1` module
 */
class ReservaaulaparahorariomateriaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\ReservaAula';
    
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {
        $bodyParams = Yii::$app->getRequest()->getBodyParams();

        $dataProvider = (new $this->modelClass)->saveBatchReservaAula($bodyParams);

        return $dataProvider;
    }
}
