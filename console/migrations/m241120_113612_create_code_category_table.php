<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%code_category}}`.
 */
class m241120_113612_create_code_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    final public function safeUp()
    {
        $this->createTable('{{%code_category}}', [
            'id' => 'int NOT NULL AUTO_INCREMENT',
            'name' => $this->string()->notNull(),
            'PRIMARY KEY(id)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    final public function safeDown()
    {
        $this->dropTable('{{%code_category}}');
    }
}
