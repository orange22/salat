<?php
/** @var $this BackController */
/** @var $languages array */

// add unique prefix to prevent ajax loaded content IDs from page IDs
$unique = '';
if(isset($forceUnique) || request()->isAjaxRequest)
    $unique = '_'.mt_rand(99, 99999);

$active = true;
$tabs = array();
$tplVars = !isset($tplVars) ? array() : $tplVars;
$tabFileName = isset($tabFileName) ? $tabFileName : '_form_language_tab';
/*
foreach($languages as $lang => $title)
{*/

    $tabs['ru'] = array(
        'id' => 'language-tab-'.$lang.$unique,
        'label' => $title,
        'active' => $active,
        'content' => $this->renderPartial(
            $tabFileName,
            CMap::mergeArray($tplVars, array(
                'model' => $models[$lang],
                'language' => $lang,
            )),
            true
        ),
    );
    $active = false;
/*}*/
$this->widget('TbTabs', array(
    'id' => 'language-tabs'.$unique,
    'type' => 'tabs',
    'tabs' => $tabs
));