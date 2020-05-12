<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

/* @var $this yii\modules\optimizers\View */
?>

<?php Pjax::begin(); ?>
    <?= Html::beginForm(['/images/optimizer/index'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
    <div class="form-group col-lg-5">
        <?= Html::input('text', 'urlimage', Yii::$app->request->post('urlimage'), ['class' => 'form-control','style'=> 'width:100%']) ?>
    </div>
        <?= Html::submitButton('Загрузить изображение', ['class' => 'btn btn-success']) ?>
    <?= Html::endForm() ?>
    <?= Html::img($path) ?>
<?php Pjax::end(); ?>
