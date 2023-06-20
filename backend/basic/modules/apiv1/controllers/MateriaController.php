<?php

namespace app\modules\apiv1\controllers;


/**
 * Default controller for the `apiv1` module
 */
class MateriaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\Materia';

    
    public function actions()
    {
        $actions = parent::actions();

        $actions['buscar'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'prepareDataProvider' => function ($action) {
                $materiaModel = new \app\modules\apiv1\models\Materia();
                $dataProvider = $materiaModel->buscar(\Yii::$app->request->queryParams);

                return $dataProvider;
            }
        ];

        return $actions;
    }
}
