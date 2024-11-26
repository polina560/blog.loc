<?php

namespace common\models;

use admin\components\parsers\ParserInterface;
use admin\components\uploadForm\models\UploadForm;
use admin\components\uploadForm\models\UploadInterface;
use common\models\AppActiveRecord;
use common\modules\user\models\User;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenSpout\Common\Entity\Cell;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
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
#[Schema(properties: [
    new Property(property: 'promocode', type: 'string'),
])]
class Code extends AppActiveRecord implements UploadInterface
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
            [
                ['code_category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CodeCategory::class,
                'targetAttribute' => ['code_category_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
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

    final public function fields(): array
    {
        return [
            'promocode',
        ];
    }


    final public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CodeCategory::class, ['id' => 'code_category_id']);
    }

    final public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @throws Exception
     */
    public static function insertFromFile(UploadForm $model, ParserInterface $parser): void
    {
        $values = [];
        $columnKeyCode = null;
        $columnKeyPromo = null;

        $parser->fileRowIterate(
            $model->file->tempName,
            /**
             * @param Cell[] $cells
             * @throws Exception
             */
            function (array $cells, int $key) use (&$values, $model, &$columnKeyCode, &$columnKeyPromo) {
                if ($key === 1) {
                    foreach ($cells as $columnKey => $cell) {
                        if(empty($cell->getValue())) {
                            Yii::$app->session->setFlash('error', "Заголовок колонки пуст");
                            return;
                        }
                        if(str_contains($cell->getValue(), 'promo'))
                            $columnKeyPromo = $columnKey;
                        if(!str_contains($cell->getValue(), 'promo') && str_contains($cell->getValue(), 'code'))
                            $columnKeyCode = $columnKey;
                    }
                    return;
                }

                $code = $cells[$columnKeyCode]->getValue();
                $promocode = $cells[$columnKeyPromo]->getValue();


                if(empty($code) || strlen($code) != 6) {
                    Yii::$app->session->setFlash('error', "Некорректный код");
                    return;
                }
                $values[] = [$code, $promocode, $model->category_id];
                if (count($values) > 10000) {
                    Yii::$app->db->createCommand()
//                        ->batchInsert(self::tableName(), ['code', 'promocode'], $values)
                        ->batchInsert(self::tableName(), ['code', 'promocode', 'code_category_id'], $values)
                        ->execute();
                    $values = [];
                }
            }
        );
        if (!empty($values)) {
            Yii::$app->db->createCommand()
//                ->batchInsert(self::tableName(), ['code', 'promocode'], $values)
                ->batchInsert(self::tableName(), ['code', 'promocode', 'code_category_id'], $values)
                ->execute();
        }
    }
}
