<?php
/** @var $this BackController */
/** @var $model BaseActiveRecord */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php $this->widget('backend.components.SeoFormWidget', array(
    'form' => $form,
    'model' => $model,
)); ?>
