<?php 
use \yii\helpers\Html;
//print('<pre>'); var_dump($model);print('<pre>');die; 
/*if (!isset($commentCount)) {
	$commentCount = 0;
	if (count($data->comments) > 0 ) $commentCount=count($data->comments);
}*/
?>
<div class="row-fluid">
	<div class="page-header">
		<h1><?php echo Html::a(Html::encode($model->title), $model->url); ?></h1>
	</div>
	<p class="meta">Posted by <?php echo $model->author->username . ' on ' . date('F j, Y',$model->create_time); ?></p>
	<p class='lead'>
		<?php
			echo $model->content;
		?>
	<p>
	<div class="row-fluid">
		<p class="tags">
			<strong>Tags:</strong>
			<?php echo  $model->tags; ?>
		</p>
		<?php echo Html::a('Permalink', $model->url); ?> |
		<?php echo Html::a("Comments ({$model->commentCount})",$model->url.'#comments'); ?> |
		Last updated on <?php echo date('F j, Y',$model->update_time); ?>
	</div>
</div>
