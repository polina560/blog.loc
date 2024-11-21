<?php

namespace api\modules\v1\controllers;

use api\behaviors\returnStatusBehavior\JsonSuccess;
use common\models\CodeCategory;
use common\modules\user\Module;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class CodeCategoryController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), ['auth' => ['except' => ['index']]]);
    }


    #[Get(
        path: '/code-category/index',
        operationId: 'code-category-index',
        description: 'Возвращает список категорий кодов',
        summary: 'Список категорий',
        security: [['bearerAuth' => []]],
        tags: ['code-categories']
    )]
    #[JsonSuccess(content: [
        new Property(
            property: 'code-category', type: 'array',
            items: new Items(ref: '#/components/schemas/CodeCategory'),
        )
    ])]
    public function actionIndex(): array
    {
        $query = CodeCategory::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->returnSuccess([
            'news' => $provider,

        ]);


    }


}
