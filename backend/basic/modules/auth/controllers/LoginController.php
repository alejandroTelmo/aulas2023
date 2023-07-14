<?php

namespace app\modules\auth\controllers;

use Yii;
use app\modules\auth\controllers\BaseController;
use app\models\User;
/**
 * Login controller for the `auth` module
 */
class LoginController extends BaseController
{
   
 
    public function actionIndex()
    {
        $request=Yii::$app->request;

        $user=User::findByUsername($request->post('username'));

        if($user){
            if($user->validatePassword($request->post('passwd'))){
                return [
                    "username"=>$user->username,
                    "accessToken"=>$user->accessToken,
                    "nombre"=>$user->nombre,
                    "apellido"=>$user->apellido,

                ];
            }
        }
        
        Yii::$app->response->statusCode=401;

        return ;
    }
}
