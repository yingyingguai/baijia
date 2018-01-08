<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m171220_135001_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->primaryKey()->comment('商品id'),
            'content'=>$this->string()->comment('商品描述'),
        ],'ENGINE=INNODB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
