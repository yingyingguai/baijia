<h1><?=$model->name?></h1>
<table class="table-bordered">
    <?php
    echo $model->content;
    ?>
    <?php foreach($model->path as $pic): ?>
        <p><img src="<?=$pic['path']?>"></p>
    <?php endforeach ?>

</table>
