<?php

namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username; //用户名
    public $password; //密码
    public $remember; //记住我
    public $code;
    public $checkcode;//图片验证码
    //定义规则
    public function rules()
    {
        return [
            [
                ['username', 'password'], 'required'
            ],
            ['remember', 'default', 'value' => null],
       //     ['checkcode','captcha','captchaAction'=>'user/captcha']
         //   ['code', 'captcha', 'captchaAction' => 'user/captcha'],
        ];
    }

    //登录验证
    public function login()
    {
//        $result = Member::find()->where(['username' => $this->username])->one();
     $result = Member::findOne(['username' => $this->username]);
        //验证用户名存在
        if ($result) {
            if (\Yii::$app->security->validatePassword($this->password, $result->password_hash)) {
                //是否记住我
                if ($this->remember == 1) {
                    \Yii::$app->user->login($result, 24 * 7 * 3600);
                } else {
                    \Yii::$app->user->login($result);
                }
                return true;
            } else {
                echo '密码错误';
            }
        } else {
            echo '用户名不正确';
        }
        //延时
        sleep(1);
        return false;
    }
}