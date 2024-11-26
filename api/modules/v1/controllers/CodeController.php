<?php

namespace api\modules\v1\controllers;


use common\modules\user\models\UserExt;
use api\behaviors\returnStatusBehavior\{JsonSuccess, RequestFormData};
use common\models\Code;
use OpenApi\Attributes\{Items, Post, Property};
use Yii;
use yii\base\Exception;


class CodeController extends AppController
{
    /**
     * Returns a list of Code's
     */
    #[Post(
        path: '/code/index',
        operationId: 'code-index',
        description: 'Присваивает промокод пользователю',
        summary: 'Промокод',
        security: [['bearerAuth' => []]],
        tags: ['promocode']
    )]
    #[RequestFormData(
        properties: [
            new Property(property: 'code', description: 'Код', type: 'string'),
        ]
    )]
    #[JsonSuccess(content: [
        new Property(
            property: 'promocode', type: 'array',
            items: new Items(ref: '#/components/schemas/Code'),
        )
    ])]
    public function actionIndex(): array
    {
        $user_id = Yii::$app->user->identity->getId();

        $this->isUserBanned($user_id);

        $code = $this->getParameterFromRequest('code');
        if(strlen($code) != 6) return $this->returnError(['Поле code должно состоять из 6 символов']);
        $codeModel = Code::find()->where(['code' => $code])->one();

        if (empty($code)) {
            return $this->returnError(['Поле code не заполнено']);
        }

        if (empty($codeModel)) {
            $this->bunUser($user_id);
            return $this->returnError(['Поле code заполнено некорректно']);
        }

        if($codeModel->load(['user_id' => $user_id],'') && $codeModel->validate()){
            $codeModel->save();
            return $this->returnSuccess([
                'code-category' =>  $codeModel]);
        }
        else {
            return [
                'success' => false,
                'errors' => $codeModel->getErrors(),
            ];
        }


    }


    public function isUserBanned($id): void
    {
        $user = UserExt::find()->where(['user_id' => $id])->one();
        if($user->banned) {
            if ($user->invalid_code == 3) {
                if (time() < ($user->banned_at + 5 * 60)) {
//                    return self::actionIndex()->$this->returnError(['Вы забанены на 5 минут. Дождитесь разблокировки.']);
                    throw new Exception('Вы забанены на 5 минут. Дождитесь разблокировки.');
                }
            }
            if ($user->invalid_code == 6) {
                if (time() < ($user->banned_at + 30 * 60)) {
//                    return $this->returnError(['Вы забанены на 30 минут. Дождитесь разблокировки.']);
                    throw new Exception('Вы забанены на 30 минут. Дождитесь разблокировки.');

                }
            }
            if ($user->invalid_code >= 9) {
//                return $this->returnError(['Вы забанены.']);
                throw new Exception('Вы забанены.');

            } else $this->removeBunUser($id);
        }
    }

    public function bunUser($id): void
    {
        $user = UserExt::find()->where(['user_id' => $id])->one();
        $user->invalid_code += 1;
        if(in_array($user->invalid_code, [3, 6, 9]))
        {
            $user->banned = 1;
            $user->banned_at = time();
        }
        $user->save();
    }

    public function removeBunUser($id): void
    {
        $user = UserExt::find()->where(['user_id' => $id])->one();

        $user->banned = 0;
        $user->banned_at = null;
        $user->save();
    }

//     ArrayHelper::getValue(Yii::$app->params, 'request_limits.' . $category);



}
