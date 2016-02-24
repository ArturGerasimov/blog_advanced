<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
   <?php  echo $this->context->renderPartial('_item', array(
    'model'=>$model
));?>

    <div id="comments" class="row-fluid">
    <?php
    if($model->commentCount>=1): ?>
        <h4>
            <?php echo $model->commentCount>1 ? $model->commentCount . ' comments' : 'One comment'; ?>
        </h4>
    
        <?php echo $this->context->renderPartial('_comments',array(
            'post'=>$model,
            'comments'=>$model->comments,
        )); ?>
    <?php endif; ?>

        <?php echo $this->context->renderPartial('/comment/_form',array(
            'model'=>$comment,
        )); ?>
   


</div><!-- comments -->

</div>
