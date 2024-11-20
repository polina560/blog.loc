<?php

namespace common\models;

use common\models\AppActiveRecord;
use common\modules\user\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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

    public UploadedFile|string|null $codes_promoList = null;
    public UploadedFile|string|null $csvFile = null;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%code}}';
    }

    public static function externalAttributes(): array
    {
        return ['category.name', 'user.username'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['code', 'promocode', 'code_category_id'], 'required'],
            [['code_category_id', 'user_id', 'taken_at', 'user_ip', 'public_status'], 'integer'],
            [['code'], 'string', 'max' => 6],
            [['promocode'], 'string', 'max' => 255],
            [['code_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CodeCategory::class, 'targetAttribute' => ['code_category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['codes_promoList'], 'string'],
            [['csvFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'csv']
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
            'user_ip' => Yii::t('app', 'User IP'),
            'public_status' => Yii::t('app', 'Public Status'),
        ];
    }

//    public function beforeSave($insert): bool
//    {
////        if ($this->codes_promoList instanceof UploadedFile) {
////
////            $randomName = Yii::$app->security->generateRandomString(8);
////            $public = Yii::getAlias('@public');
////            $path = '/uploads/' . $randomName . '.' . $this->imageFile->extension;
////            $this->imageFile->saveAs($public . $path);
////            $this->image = $path;
////        }
////        return parent::beforeSave($insert);
//    }


    final public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CodeCategory::class, ['id' => 'code_category_id']);
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
