<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Html;
/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property string $author
 * @property string $email
 * @property string $url
 * @property integer $post_id
 *
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{

    const STATUS_PENDING=1;
    const STATUS_APPROVED=2;
    

    public static function tableName()
    {
        return 'comment';
    }


    public function rules()
    {
        return [
        [['content', 'author', 'email'], 'required'],
        [['author', 'email', 'url'], 'string', 'max' => 128],
        ['email','email'],
        [['content'], 'string'],
        ['url','url'],
        [['status', 'create_time', 'post_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'content' => 'Comment',
        'status' => 'Status',
        'create_time' => 'Create Time',
        'author' => 'Name',
        'email' => 'Email',
        'url' => 'Website',
        'post_id' => 'Post',
        ];
    }

    public function behaviors(){
        return [
        'timestamp' => [
        'class' => TimestampBehavior::className(),
        'attributes' => [
        ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
            //ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
        ],
        ]   
        ];
    }

        
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /* подтверждает комментарий */

    public function approve()
    {
        $this->status=Comment::STATUS_APPROVED;
        $this->update(['status']);
    }

    /* присваивает комментарий */

    public function getUrl($post=null)
    {
        if($post===null)
            $post=$this->post;
        return $post->url.'#c'.$this->id;
    }


    /* Возвращает строку для вывода комментария */

    public function getAuthorLink()
    {
        if(!empty($this->url))
            return Html::a(Html::encode($this->author),$this->url);
        else
            return Html::encode($this->author);
    }


    /* Возвращет макс. число комментариев */

    public static function findRecentComments($limit=10)
    {
        return static::find()->where('status='.self::STATUS_APPROVED)
                    ->orderBy('create_time DESC')
                    ->limit($limit)
                    ->with('post')->all();
    }
}