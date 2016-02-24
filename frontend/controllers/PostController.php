<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\PostSearch;
use common\models\Comment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public $layout='column2';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout='column1';
        $model = $this->findModel($id);
        //$comment=$this->newComment($model);
        $comment=new Comment();
        if($comment->load($_POST) && $model->addComment($comment))
            {
                if($comment->status==Comment::STATUS_PENDING){
                    Yii::$app->getSession()->setFlash('warning','Спасибо! Ваш комментарий будет опублиикован после подтверждения');
                }
                return $this->refresh();
            }
        return $this->render('view',array(
            'model'=>$model,
            'comment'=>$comment,
        ));
    }
  

   

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (
                ($model = Post::find()->where(['id'=>$id])->
                andWhere(['IN','status', [Post::STATUS_PUBLISHED , Post::STATUS_ARCHIVED]])->
                one()) !== null
            ) 
        {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   protected function newComment($post)
    {
        $comment=new Comment();
        if($comment->load($_POST) && $post->addComment($comment))
        {
            if($comment->status==Comment::STATUS_PENDING){
                Yii::$app->getSession()->setFlash('commentSubmitted','Thank you for your comment. Your comment will be posted once it is approved.');
                $s=Yii::$app->session->getFlash('commentSubmitted');
                print('<pre>'); var_dump($s);print('<pre>');die; 
            }
            //return Yii::$app->response->refresh();
            return $this->refresh();

            //$this->redirect(\Yii::$app->request->getReferrer());
        }
        return $comment;
    }

     public function actionTest()
    {
        $t=Yii::$app->params['commentNeedApproval'];
        var_dump($t);
    }
}
