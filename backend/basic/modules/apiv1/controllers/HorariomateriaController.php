<?php

namespace app\modules\apiv1\controllers;

use Yii;

/**
 * Default controller for the `apiv1` module
 */
class HorariomateriaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\HorarioMateria';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'prepareDataProvider' => function ($action) {

                $requestParams = Yii::$app->getRequest()->getBodyParams();
                if (empty($requestParams)) {
                    $requestParams = Yii::$app->getRequest()->getQueryParams();
                }

                $dataProvider = (new $this->modelClass)->find()
                    ->getWithMateria()
                    ->paginate($requestParams['page'], $requestParams['per-page'])
                    ->orderBy($requestParams['sort']);

                Yii::$app->response->headers->set('X-Pagination-Current-Page', $dataProvider->getPage());
                Yii::$app->response->headers->set('X-Pagination-Page-Count', $dataProvider->getPerPage());
                Yii::$app->response->headers->set('X-Pagination-Total-Count', $dataProvider->getTotalCount());

                return $dataProvider->asArray()->all();
            }
        ];
        return $actions;
    }
}
