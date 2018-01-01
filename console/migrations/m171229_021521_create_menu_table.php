<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171229_021521_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(20)->notNull()->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级菜单'),
            'url'=>$this->string(255)->comment('地址/路由'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
