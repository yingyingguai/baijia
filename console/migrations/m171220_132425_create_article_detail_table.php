<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m171220_132425_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->primaryKey()->comment('文章id'),
            'content'=>$this->string()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
