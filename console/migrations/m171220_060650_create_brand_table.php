<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171220_060650_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            //Id    name  intro  logo  sort  status
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('姓名'),
            'intro'=>$this->string()->comment('简介'),
            'logo'=>$this->string(255)->comment('logo'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(1)->comment('状态 -1删除 0 隐藏 1 显示'),

        ],'ENGINE=INNODB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
