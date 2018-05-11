<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use chabibnr\changePassword\models\ChangePasswordForm;
/** @var \yii\web\View $this */
/** @var $optionsActiveForm array*/

$this->title = "Ganti Kata Sandi";
?>

<?php $form = ActiveForm::begin($optionsActiveForm); ?>
<?php
if($model->getScenario() == ChangePasswordForm::SCENARIO_WITH_PASSWORD) {
    echo $form->field($model, 'old_password')->passwordInput();
}
?>
<?= $form->field($model, 'new_password')->passwordInput() ?>
<?= $form->field($model, 'confirm_password')->passwordInput() ?>
<div class="form-group">
    <?= Html::submitButton('Ganti Kata Sandi', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
