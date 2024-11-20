<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%code}}`.
 */
class m241120_113622_create_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    final public function safeUp()
    {
        $this->createTable('{{%code}}', [
            'id' => 'int NOT NULL AUTO_INCREMENT',
            'code' => $this->string(6)->notNull(),
            'promocode' => $this->string(255)->notNull(),
            'code_category_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'taken_at' => $this->integer(),
            'user_ip' => $this->integer(),
            'public_status' => $this->boolean(),
            'PRIMARY KEY(id)'

        ]);

        $this->addForeignKey('FK_code_category', '{{%code}}', 'code_category_id',
            '{{%code_category}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_code_user', '{{%code}}', 'user_id',
            '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    final public function safeDown()
    {
        $this->dropTable('{{%code}}');
    }
}
