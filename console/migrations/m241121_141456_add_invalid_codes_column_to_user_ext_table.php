<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_ext}}`.
 */
class m241121_141456_add_invalid_codes_column_to_user_ext_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(
            '{{%user_ext}}',
            'invalid_code',
            $this->integer()->defaultValue(0)->comment('Количество неправильно введенных кодов')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('{{%user_ext}}', 'invalid_code');
    }
}
