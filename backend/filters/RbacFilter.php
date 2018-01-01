<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;
class RbacFilter extends ActionFilter{
    //操作执行之前
    public function beforeAction($action)
    {
        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果用户没有登录,则引导用户登录
            if(\Yii::$app->user->isGuest){
                //跳转到登录页
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //没有权限
            throw new HttpException(403,'对不起,您没有该操作权限!');
        }
        return true;
    }
}