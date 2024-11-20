<?php

use admin\widgets\ckfinder\CKFinderInputFile;
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

<div class="code-modal-form">

    <?php $form = AppActiveForm::begin() ?>

    <?php $categories = new \common\models\CodeCategory();?>



    <?= $form->field($model, 'csvFile')->widget(CKFinderInputFile::class, ['resourceType' => 'FilesCSV', 'isImage' => false]); ?>




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
