<?php
use Yii;
use frontend\components\RecentComments;
/*use app\widgets\UserMenu;
use app\widgets\TagCloud;
use app\widgets\RecentComments;*/

$this->beginContent('@frontend/views/layouts/main.php'); ?>
<div class="row">
	<div class="col-md-9">
		<?php echo $content; ?>
	</div>
	<div class="col-md-3">
		<?= RecentComments::widget();?>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt non, voluptates blanditiis aliquam fugiat numquam neque optio praesentium? Aliquid sint voluptatem soluta optio! Voluptatem, totam molestias eveniet, sapiente dolorum soluta.</p>
	</div>
	</div>
<?php $this->endContent(); ?>
