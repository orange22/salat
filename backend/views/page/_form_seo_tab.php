<?php
/** @var $this PageController */
/** @var $model Page */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php $this->widget('backend.components.SeoFormWidget', array(
    'form' => $form,
    'model' => $model,
    'language' => $language,
)); ?>