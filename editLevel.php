<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<a href="<?php echo Url::toRoute(['site/productlist']);?>">Back</a>
<div class="site-signup">
 <div class="row">
        <div class="col-lg-5">
           <?php $form = ActiveForm::begin(['id' => 'form-level']); ?>
           <label></label>
           <?= $form->field($model, 'queenLevel')->textInput(['autofocus' => true, 'label'=>false])
            ?>
           <?= $form->field($model, 'ronLevel')->textInput(['autofocus' => true]) ?>
           <?= $form->field($model, 'wilsonLevel')->textInput(['autofocus' => true]) ?>
             <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
              </div>
              <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<style type="text/css">
  #load{
    display: none;
  }
</style>