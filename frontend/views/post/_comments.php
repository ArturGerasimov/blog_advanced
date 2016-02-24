<?php 
use \yii\helpers\Html;

foreach($comments as $comment): ?>
<div class="well" id="c<?php echo $comment->id; ?>">
	<div class="row">
		<div class="col-md-8">
			<h4><?php echo $comment->authorLink; ?> says:</h4>
		</div>
		<div class="col-md-4 text-right">
			<?php echo Html::a("#{$comment->id}", $comment->getUrl(),[ 
				'class'=>'cid',
				'title'=>'Permalink to this comment!',
				]); 
				 ?>
		</div>
	</div>
	<hr style="margin:2px 0px;">
	<p class='lead'>
		<?php echo nl2br(Html::encode($comment->content)); ?>
	</p>
	<h5>
		<?php echo date('F j, Y \a\t h:i a',$comment->create_time); ?>
	</h5>
	

</div><!-- comment -->
<?php endforeach; ?>