<?php

namespace common\models;

use common\models\AppActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%code}}".
 *
 * @property int               $id
 * @property string            $code
 * @property string            $promocode
 * @property int               $code_category_id
 * @property int|null          $user_id
 * @property int|null          $taken_at
 * @property int|null          $user_ip
 * @property int|null          $public_status
 *
 * @property-read CodeCategory $codeCategory
 * @property-read User         $user
 */
class Code extends AppActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['code', 'promocode', 'code_category_id'], 'required'],
            [['code_category_id', 'user_id', 'taken_at', 'user_ip', 'public_status'], 'integer'],
            [['code', 'promocode'], 'string', 'max' => 6],
            [['code_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CodeCategory::class, 'targetAttribute' => ['code_category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    final public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'promocode' => Yii::t('app', 'Promocode'),
            'code_category_id' => Yii::t('app', 'Code Category ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'taken_at' => Yii::t('app', 'Taken At'),
            'user_ip' => Yii::t('app', 'User Ip'),
            'public_status' => Yii::t('app', 'Public Status'),
        ];
    }

    final public function getCodeCategory(): ActiveQuery
    {
        return $this->hasOne(CodeCategory::class, ['id' => 'code_category_id']);
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
