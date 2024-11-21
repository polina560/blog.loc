<?php

namespace common\models;

use common\models\AppActiveRecord;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%code_category}}".
 *
 * @property int         $id
 * @property string      $name
 *
 * @property-read Code[] $codes
 */
#[Schema(properties: [
    new Property(property: 'id', type: 'integer'),
    new Property(property: 'name', type: 'string'),
])]

class CodeCategory extends AppActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%code_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    final public function fields(): array
    {
        return [
            'id',
            'name'
        ];
    }

    /**
     * {@inheritdoc}
     */
    final public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    public function getCategoriesNameArray()
    {
        $names = self::find()->select(['id', 'name'])->asArray()->all();


        return array_column($names, 'name', 'id');
    }



    final public function getCodes(): ActiveQuery
    {
        return $this->hasMany(Code::class, ['code_category_id' => 'id']);
    }
}
