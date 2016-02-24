<?php
/**
 * Created by Saflor
 * User: Tilhom
 * Date: 19.10.2015
 * Time: 6:32 
 */
namespace frontend\components; 

use yii\base\Widget;
use common\models\Comment;

class RecentComments extends Widget
{
	public $comments;
	public function init()
	{
		parent::init();
		$this->comments =  Comment::findRecentComments();
		//print('<pre>'); var_dump($this->comments);print('<pre>');die; 
	}

	public function run()
	{
		return $this->render('recent-comments');
	}
	
}
