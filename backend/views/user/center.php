<?php
if(\Yii::$app->user->identity){
    echo '欢迎'.Yii::$app->user->identity->username.'来到管理员中心',"<br>";
//    echo  \yii\bootstrap\Html::a('退出登录',['user/logout']),"<br>";
    echo \yii\bootstrap\Html::a('修改密码',['user/edit']);
}else{
    echo '未登录,请去登录';
    echo \yii\bootstrap\Html::a('登录',['user/login']);
}
?>