<?php
/** @var $this SiteController */
$baseUrl = Yii::app()->baseUrl;
$cs = cs();

$cs->registerScriptFile($baseUrl.'/backend/js/main.js?v=1', CClientScript::POS_END);
$cs->registerCssFile($baseUrl.'/backend/css/main.css?v=1', 'screen');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php if(method_exists($this, 'getTitle')) { ?>
        <title><?php echo CHtml::encode(Yii::app()->name
            .' - '.Yii::t('backend', '{title}', array('{title}' => Yii::t('backend', $this->getTitle())))
            .' - '.$this->pageTitle
        ); ?></title>
    <?php } else { ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php if(!user()->isGuest) { ?>
    <?php $this->widget('TbNavbar', array(
        'collapse' => true,
        'brand' => CHtml::encode(Yii::app()->name),
        'brandUrl' => array('/order'),
        'items' => array(
            array(
                'class' => 'TbMenu',
                'items' => array(

                    array('label' => Yii::t('backend', 'Categories'),
                        'url' => array('/category'),
                        'visible' => user()->checkAccess('Category.*'),
                        'active' => $this->getId() === 'category',
                    ),

                    array('label' => Yii::t('backend', 'Products'),
                        'url' => array('/prod'),
                        'visible' => user()->checkAccess('Product.*'),
                        'active' => $this->getId() === 'prod',
                    ),

                    array('label' => Yii::t('backend', 'Options'),
                        'url' => '#',
                        'visible' => user()->checkAccess('Option.Admin'),
                        'active' => in_array($this->getId(), array('option', 'options')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Common'),
                                'url' => array('options/optionGroup', 'group' => 'Common'),
                                'visible' => user()->checkAccess('Options.*'),
                                'active' => $this->getId() === 'options' && request()->getQuery('group') === 'Common',
                            ),
                            array('label' => Yii::t('backend', 'Options'),
                                'url' => array('/option'),
                                'visible' => user()->checkAccess('Option.*'),
                                'active' => $this->getId() === 'option',
                            ),
                        )
                    ),

                    array('label' => Yii::t('backend', 'System'),
                        'url' => '#',
                        'visible' => user()->checkAccess('User.*'),
                        'active' => in_array($this->getId(), array('option', 'language', 'file', 'user', 'rights')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Options'),
                                'url' => array('/option'),
                                'visible' => user()->checkAccess('Option.*'),
                                'active' => $this->getId() === 'option',
                            ),
                            array('label' => Yii::t('backend', 'Files'),
                                'url' => array('/file'),
                                'visible' => user()->checkAccess(Rights::module()->superuserName),
                                'active' => $this->getId() === 'file',
                            ),
                             array('label' => Yii::t('backend', 'Usertype'),
                                'url' => array('/usertype'),
                                'visible' => user()->checkAccess('Usertype.*'),
                                'active' => $this->getId() === 'usertype',
                            ),
                            array('label' => Yii::t('backend', 'Users'),
                                'url' => array('/user'),
                                'visible' => user()->checkAccess('User.*'),
                                'active' => $this->getId() === 'user',
                            ),
                            array('label' => Yii::t('backend', 'Subscribers'),
                                'url' => array('/subscriber'),
                                'visible' => user()->checkAccess('Subscriber.*'),
                                'active' => $this->getId() === 'subscriber',
                            ),
                        )
                    ),

                    array('label' => Yii::t('backend', 'Login'),
                        'url' => array('/default/login'),
                        'visible' => user()->isGuest
                    ),
                ),
            ),
            array(
                'class' => 'TbMenu',
                'items' => array(
                    '---',
                    array('label' => Yii::t('backend', 'Go to site'), 'url' => '/', 'linkOptions' => array('target' => '_blank')),
                ),
            ),

            '<p class="navbar-text pull-right">'
                .Yii::t('backend', 'Logged in as <strong>{name}</strong>', array('{name}' => user()->getDisplayName()))
                .' <a href="'.$this->createUrl('/site/logout').'">'.Yii::t('backend', '(logout)').'</a></p>',
        ),
    )); ?>
<?php } ?>

<div class="container-fluid">

    <?php echo $content; ?>

    <footer>
    </footer>

</div>
<!-- .container-fluid -->

</body>
</html>