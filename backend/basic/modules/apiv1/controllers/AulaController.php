<?php

namespace app\modules\apiv1\controllers;


/**
 * Default controller for the `apiv1` module
 */
class AulaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\Aula';

    public function actions()
    {
        $actions = parent::actions();

        $actions['todos'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'prepareDataProvider' => function ($action) {
                $aulaModel = new \app\modules\apiv1\models\Aula();
                $dataProvider = $aulaModel->todos();

                return $dataProvider;
            }
        ];

        return $actions;
    }
}
