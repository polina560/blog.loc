<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_ext}}`.
 */
class m241125_061700_add_banned_columns_to_user_ext_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%user_ext}}',
            'banned',
            $this->boolean()->defaultValue(0)->comment('Флаг бана пользователя')
        );
        $this->addColumn(
            '{{%user_ext}}',
            'banned_at',
            $this->integer()->comment('Время бана пользователя')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_ext}}', 'banned');
        $this->dropColumn('{{%user_ext}}', 'banned_at');
    }
}
