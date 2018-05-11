<?php
namespace chabibnr\changePassword;


use chabibnr\changePassword\models\ChangePasswordForm;
use yii\base\Widget;
use Yii;

class Form extends Widget {

    public $scenario;
    public $optionsActiveForm = [];
    public $customView = 'form-password';
    public $minPassword = null;
    public $maxPassword = null;

    const EVENT_SUCCESS_CHANGE_PASSWORD = 'success';

    public function init() {
        parent::init();
    }

    public function run() {
        $model = new ChangePasswordForm();
        $model->min = $this->minPassword;
        $model->max = $this->maxPassword;
        $model->setScenario(empty($this->scenario) ? ChangePasswordForm::SCENARIO_WITH_PASSWORD : ChangePasswordForm::SCENARIO_WITHOUT_PASSWORD);

        if($model->load(Yii::$app->getRequest()->post()) && $model->save()){
            $this->trigger(static::EVENT_SUCCESS_CHANGE_PASSWORD);
        }
        echo $this->render($this->customView,[
            'model' => $model,
            'optionsActiveForm' => $this->optionsActiveForm
        ]);
    }
}