<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\Tag;
use common\models\Comment;
/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 *
 * @property Comment[] $comments
 * @property User $author
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_DRAFT=1;
    const STATUS_PUBLISHED=2;
    const STATUS_ARCHIVED=3;

    private $_oldTags;

    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            ['title','string','max'=>128],
            ['status','in', 'range'=>[1,2,3]],
            ['tags', 'match', 'pattern'=>'/^[\w\s,]+$/',
            'message'=>'В тегах можно использовать только буквы.'],
            ['tags', function($attribute,$params){
                $this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
            }],

        ];
    }


    public function behaviors(){
        return [
        'timestamp' => [
            'class' => TimestampBehavior::className(),
            'attributes' => [
            ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
            ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
            ],
        ],
        [
        'class' => BlameableBehavior::className(),
        'createdByAttribute' => 'author_id',
        'updatedByAttribute' => 'author_id',
        ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'title' => 'Title',
        'content' => 'Content',
        'tags' => 'Tags',
        'status' => 'Status',
        'create_time' => 'Create Time',
        'update_time' => 'Update Time',
        'author_id' => 'Author ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
     public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])
        ->where('status = '. Comment::STATUS_APPROVED)
                    ->orderBy('create_time DESC');
    }
    public function getCommentCount()
    {
        return $this->hasMany(Comment::className(), 
            ['post_id' => 'id'])->where(['status'=>Comment::STATUS_APPROVED])->count();
    }
    public function getAllCommentCount()
    {
        return $this->hasMany(Comment::className(), 
            ['post_id' => 'id'])->where(['status'=>Comment::STATUS_APPROVED])->count();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
     public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
    public function getUrl()
    {
        return Yii::$app->urlManager->createUrl([ 'post/view', 
            'id'=>$this->id,
            'title'=>$this->title]);
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::updateFrequency($this->_oldTags, $this->tags);
   
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags=$this->tags;
    }
    /**
     * Добавление нового комментария.
     * метод задает статус и id поста
     */
    public function addComment($comment)
    {
        if(Yii::$app->params['commentNeedApproval'])
            $comment->status=Comment::STATUS_PENDING;
        else
            $comment->status=Comment::STATUS_APPROVED;
        $comment->post_id=$this->id;
        //print('<pre>'); var_dump($comment);print('<pre>');die; 
        return $comment->save();
    }
}