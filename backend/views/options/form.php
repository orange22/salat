<?php
/** @var $this OptionsController */
/** @var $model OptionsForm */
/** @var $tplVars array */

$args = CMap::mergeArray(array(
    'options' => $model->getOptions(),
    'legend' => Yii::t('cp', $group),
), $tplVars);

try
{
    $this->renderPartial('_'.$name, $args);
}
catch(CException $e)
{
    $this->renderPartial('_form', $args);
}