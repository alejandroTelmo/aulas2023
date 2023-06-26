<?php

namespace app\modules\apiv1\controllers;

use Yii;


/**
 * Default controller for the `apiv1` module
 */
class ReservaaulaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\ReservaAula';
    
    public function actions()
    {
        $actions = parent::actions();

        $actions['calendario'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'prepareDataProvider' => function ($action) {
                $reservaAulaModel = new \app\modules\apiv1\models\ReservaAula();
                $dataProvider = $reservaAulaModel->calendario(Yii::$app->request->queryParams);
                return $dataProvider;
            }
        ];

        return $actions;
    }
}
