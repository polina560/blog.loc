<?php

namespace api\modules\v1\controllers;


use common\modules\user\models\UserExt;
use api\behaviors\returnStatusBehavior\{JsonSuccess, RequestFormData};
use common\models\Code;
use OpenApi\Attributes\{Items, Post, Property};
use Yii;



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
        $code = $this->getParameterFromRequest('code');
        // валидация
        $user_id = Yii::$app->user->identity->getId();
        $user = UserExt::find()->where(['user_id' => $user_id])->one();

        $codeModel = Code::find()->where(['code' => $code])->one();

        if (empty($code) || empty($codeModel)) {
            return $this->returnError(['Поле code не заполнено']);
        }

        if (empty($codeModel)) {
            $user->invalid_code += 1;
            $user->save();
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

//    public function isUserBanned(UserExt $user)
//    {
//        // Не забанен ли уже
//        $currentTime = time();
//        if ($user->banned_at > time() - $params['period']) {
//
//        }
//
//    }
//
    public function banUser(UserExt $user)
    {
        if($user->invalid_code == 3)
        {
            $user->banned = 1;
            $user->banned_at = time();
            $user->save();

        }
//        return $this->returnError('Подождите N времени');
    }
//
//    public function removeBan()
//    {
//        $user->banned = 1;
//        $user->banned_at = null;
//        $user->save();
//    }

    //фун-ии 1) проверить забаненность, забанить, снять бан
//     ArrayHelper::getValue(Yii::$app->params, 'request_limits.' . $category);



}
