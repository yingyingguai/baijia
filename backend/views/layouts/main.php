<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '京东',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label'=>'品牌管理',
            'items'=>[
                ['label' => '品牌列表', 'url' =>['brand/index']],
                ['label' => '品牌添加', 'url' =>['brand/add']],
            ],
        ],

        ['label' => '文章管理',
            'items'=>[
                ['label' => '文章列表', 'url' =>['article/index']],
                ['label' => '文章添加', 'url' =>['article/add']],
         ]],
        ['label' => '文章分类',
            'items'=>[
                ['label' => '文章分类列表', 'url' =>['article-category/index']],
                ['label' => '文章分类添加', 'url' =>['article-category/add']],
         ]],
        ['label' => '商品分类',
            'items'=>[
                ['label' => '商品分类列表', 'url' =>['goods-category/index']],
                ['label' => '商品分类添加', 'url' =>['goods-category/add']],
         ]],
        ['label' => '商品',
            'items'=>[
                ['label' => '商品列表', 'url' =>['goods/index']],
                ['label' => '商品添加', 'url' =>['goods/add']],
         ]],
           ['label' => '用户列表',
            'items'=>[
                ['label' => '用户列表', 'url' =>['user/index']],
                ['label' => '用户添加', 'url' =>['user/add']],
         ]],
      ['label' => 'RBAC',
            'items'=>[
                ['label' => '权限列表', 'url' =>['rbac/index-permission']],
                ['label' => '权限添加', 'url' =>['rbac/add-permission']],
                ['label' => '角色列表', 'url' =>['rbac/index-role']],
                ['label' => '角色添加', 'url' =>['rbac/add-role']],
         ]],

        ['label' => '个人中心', 'url' => ['user/center',],],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['user/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['user/logout'], 'post')
            . Html::submitButton(
                '退出 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 我的商城 <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
