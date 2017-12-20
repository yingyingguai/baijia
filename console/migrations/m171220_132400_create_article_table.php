<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171220_132400_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('姓名'),
            'intro'=>$this->string(255)->comment('简介'),
            'article_category_id'=>$this->integer()->comment('文章分类id'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态 -1 删除 0 隐藏 1 正常'),
            'create_time'=>$this->integer(11)->comment('创建时间'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
