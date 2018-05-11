<?php

namespace chabibnr\changePassword\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\IdentityInterface;

class ChangePasswordForm extends Model {

    const SCENARIO_WITH_PASSWORD = 'SCHENARION_WP';
    const SCENARIO_WITHOUT_PASSWORD = 'SCHENARION_WTP';

    public $user;
    public $old_password;
    public $new_password;
    public $confirm_password;

    public $min = null;
    public $max = null;

    protected $_user;

    public function rules() {
        return [
            [['old_password','new_password','confirm_password'], 'required', 'on' => static::SCENARIO_WITH_PASSWORD],
            [['new_password','confirm_password'], 'required', 'on' => static::SCENARIO_WITHOUT_PASSWORD],
            [['new_password','confirm_password'],'string', 'min' => $this->min, 'max' => $this->max],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Konfirmasi kata sandi harus sama dengan kata sandi baru'],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_WITH_PASSWORD] = ['old_password','new_password','confirm_password'];
        $scenarios[static::SCENARIO_WITHOUT_PASSWORD] = ['new_password','confirm_password'];
        return $scenarios;
    }

    public function attributeLabels() {
        return [
            'old_password' => 'Kata sandi lama',
            'new_password' => 'Kata sandi baru',
            'confirm_password' => 'Konfirmasi kata sandi',
        ];
    }

    public function getUser(){
        if($this->_user != null){
            return $this->_user;
        }

        if($this->user != null && $this->user instanceof IdentityInterface){
            $this->_user = $this->user;
        }elseif($this->user != null && !($this->user instanceof IdentityInterface)){
            throw new InvalidConfigException("konfigurasi user harus mengimplementasikan IdentityInterface");
        }else{
            $this->_user = Yii::$app->getUser()->getIdentity();
        }

        return $this->_user;
    }

    public function isPasswordValid($attribute, $params){
        $isValid = $this->getUser()->validatePassword($this->old_password);
        if(!$isValid){
            $this->addError('old_password', 'Kata sandi lama tidak sesuai');
            return false;
        }else{
            return true;
        }
    }

    public function save(){
        $user = $this->getUser();
        if(($this->getScenario() == static::SCENARIO_WITH_PASSWORD) && !$user->validatePassword($this->old_password)){
            $this->addError('old_password', 'Kata sandi lama tidak sesuai');
            return false;
        }

        $user->setPassword($this->new_password);
        return $user->save();

    }

}
