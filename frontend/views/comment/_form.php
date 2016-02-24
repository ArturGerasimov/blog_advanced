<?php
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;
?>


<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">Leave a Comment</h3>
	</div>
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<?php echo $form->field($model,'author')->textInput(); ?>
		<?php echo $form->field($model,'email')->textInput(); ?>
		<?php echo $form->field($model,'url')->textInput(); ?>
		<?php echo $form->field($model,'content')->textArea(array('rows'=>6, 'cols'=>50)); ?>
		<div class="form-actions text-center">
			<?php echo Html::submitButton('Save',['class' => 'btn btn-success btn-block']); ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>


