<?php

use admin\components\GroupedActionColumn;
use admin\components\uploadForm\UploadFormWidget;
use admin\components\widgets\gridView\Column;
use admin\components\widgets\gridView\ColumnSelect2;
use admin\modules\rbac\components\RbacHtml;
use admin\widgets\sortableGridView\SortableGridView;
use kartik\grid\SerialColumn;
use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * @var $this         yii\web\View
 * @var $searchModel  common\models\CodeSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Code
 */

$this->title = Yii::t('app', 'Codes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-index">

    <h1><?= RbacHtml::encode($this->title) ?></h1>

    <div class="form-group">
        <?=
        //            RbacHtml::a(Yii::t('app', 'Download Code'), ['create'], ['class' => 'btn btn-success']);
        $this->render('_create_modal', ['model' => $model]);
        ?>

        <?= UploadFormWidget::widget([
            'action' => Url::to(['upload']),
            'btnMessage' => 'Загрузить из файла',
            'title' => 'Загрузить коды',
        ]) ?>
    </div>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

            Column::widget(),
            Column::widget(['attr' => 'code']),
            Column::widget(['attr' => 'promocode']),
            Column::widget(['attr' => 'code_category_id', 'viewAttr' => 'category.name']),
            Column::widget(['attr' => 'user_id', 'viewAttr' => 'user.username']),
//            Column::widget(['attr' => 'taken_at']),
//            Column::widget(['attr' => 'user_ip']),
//            ColumnSelect2::widget(['attr' => 'public_status','items' => \common\enums\IssueStatus::class, 'hideSearch' => true]),

            ['class' => GroupedActionColumn::class]
        ]
    ]) ?>
</div>
