<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $email;
    public $status;


    private $_user = false;



    public function rules() // правила для полей в форме
    {
        return [
           
            [['username', 'password'], 'required', 'on' => 'default'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],// либо тру либо фолс
            ['password', 'validatePassword'], //сравнивает пароль с бд
        ];
    }

    /**
     * Функция для провекти пароля, $attribute, в данном случае password,
       будет назначена ошибка, в случае ввода неправильного пароля
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) : //если нет ошибок валидации
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) :
                $this->addError($attribute, 'Неправильно');

            endif;
        endif;    
    }

    protected function getUser()
    {
        if ($this->_user === false) :
            $this->_user = User::findByUsername($this->username);
        endif;
        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) :
            $this->status = ($user = $this->getUser()) ? $user->status : User::STATUS_NOT_ACTIVE;
            if ($this->status === User::STATUS_ACTIVE):
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            else: 
                return false;
            endif;
        else:
            return false;
        endif;
    }
}
