<?php

use admin\widgets\input\Select2;
use common\widgets\AppActiveForm;
use kartik\icons\Icon;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/**
 * @var $this     yii\web\View
 * @var $model    common\models\Code
 * @var $form     AppActiveForm
 * @var $isCreate bool
 */
?>

<div class="code-form">

    <?php $form = AppActiveForm::begin() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promocode')->textInput(['maxlength' => true]) ?>

    <?php $categories = new \common\models\CodeCategory();?>

    <?= $form->field($model, 'code_category_id')->dropDownList($categories->getCategoriesNameArray()) ?>

    <?php $users = new \common\modules\user\models\User();?>

    <?= $form->field($model, 'user_id')->dropDownList($users->getUsersNameArray()) ?>

    <?= $form->field($model, 'taken_at')->textInput() ?>

    <?= $form->field($model, 'user_ip')->textInput() ?>

    <?= $form->field($model, 'public_status')->widget(
        Select2::class,
        ['data' => \common\enums\IssueStatus::indexedDescriptions(), 'hideSearch' => true]) ?>

    <div class="form-group">
        <?php if ($isCreate) {
            echo Html::submitButton(
                Icon::show('save') . Yii::t('app', 'Save And Create New'),
                ['class' => 'btn btn-success', 'formaction' => Url::to() . '?redirect=create']
            );
            echo Html::submitButton(
                Icon::show('save') . Yii::t('app', 'Save And Return To List'),
                ['class' => 'btn btn-success', 'formaction' => Url::to() . '?redirect=index']
            );
        } ?>
        <?= Html::submitButton(Icon::show('save') . Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php AppActiveForm::end() ?>

</div>
