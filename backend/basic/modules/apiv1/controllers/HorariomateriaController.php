<?php

namespace app\modules\apiv1\controllers;

use app\modules\apiv1\models\HorarioMateria;
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

        /*         $actions['index'] = [
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
        ]; */
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        $transaction = HorarioMateria::getDb()->beginTransaction();
        try {
            foreach ($requestParams as $value) {
                $horarioMateria = new HorarioMateria();
                $horarioMateria->attributes = $value;
                $horarioMateria->save();
            }
            $transaction->commit();
            return $this->asJson([
                'success' => true,
                'data' => $requestParams,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionIndex()
    {
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
}
