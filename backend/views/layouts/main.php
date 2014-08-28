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
                    /*array('label' => Yii::t('backend', 'Pages'),
                        'url' => '#',
                        'visible' => user()->checkAccess('Page.Admin'),
                        'active' => in_array($this->getId(), array('page', 'About')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Pages'),
                                'url' => array('/page'),
                                'visible' => user()->checkAccess('Page.Admin'),
                                'active' => $this->getId() === 'page',
                            ),
                            array('label' => Yii::t('backend', 'About'),
                                'url' => array('/about'),
                                'visible' => user()->checkAccess('About'),
                                'active' => $this->getId() === 'about',
                            ),
                        )
                    ),*/

                    array('label' => Yii::t('backend', 'Kitchen'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Product.*'),
                        'active' => in_array($this->getId(), array('product', 'designer', 'discount')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Dishtype'),
                                'url' => array('/dishtype'),
                                'visible' => user()->checkAccess('Dishtype.*'),
                                'active' => $this->getId() === 'dishtype',
                            ),
                            array('label' => Yii::t('backend', 'Dish'),
                                'url' => array('/dish'),
                                'visible' => user()->checkAccess('Dish.*'),
                                'active' => $this->getId() === 'dish',
                            ),
                             array('label' => Yii::t('backend', 'Course'),
                                'url' => array('/course'),
                                'visible' => user()->checkAccess('Course.*'),
                                'active' => $this->getId() === 'course',
                            ),
                            array('label' => Yii::t('backend', 'Ingredient'),
                                'url' => array('/ingredient'),
                                'visible' => user()->checkAccess('Ingredient.*'),
                                'active' => $this->getId() === 'ingredient',
                            ),
                            array('label' => Yii::t('backend', 'Step'),
                                'url' => array('/step'),
                                'visible' => user()->checkAccess('Step.*'),
                                'active' => $this->getId() === 'step',
                            ),
                            array('label' => Yii::t('backend', 'Video'),
                                'url' => array('/video'),
                                'visible' => user()->checkAccess('Video.*'),
                                'active' => $this->getId() === 'video',
                            ),
                            array('label' => Yii::t('backend', 'Drink'),
                                'url' => array('/drink'),
                                'visible' => user()->checkAccess('Drink.*'),
                                'active' => $this->getId() === 'drink',
                            ),
                            array('label' => Yii::t('backend', 'Cookware'),
                                'url' => array('/cookware'),
                                'visible' => user()->checkAccess('Cookware.*'),
                                'active' => $this->getId() === 'cookware',
                            ),
                        ),
                    ),
                    array('label' => Yii::t('backend', 'Blog'),
                        'url' => array('/blog'),
                        'visible' => user()->checkAccess('Blog.*'),
                        'active' => $this->getId() === 'blog',
                    ),

                    array('label' => Yii::t('backend', 'Other'),
                        'url' => array('/tools'),
                        'visible' => user()->checkAccess('Other.Admin'),
                        'active' => in_array($this->getId(), array('producttype', 'product','tools','reply')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Product Types'),
                                'url' => array('/producttype'),
                                'visible' => user()->checkAccess('Product Type.*'),
                                'active' => $this->getId() === 'producttype',
                            ),
                            array('label' => Yii::t('backend', 'Products'),
                                'url' => array('/product'),
                                'visible' => user()->checkAccess('Product.*'),
                                'active' => $this->getId() === 'product',
                            ),
                            array('label' => Yii::t('backend', 'Tools'),
                                'url' => array('/tools'),
                                'visible' => user()->checkAccess('Tools.Admin'),
                                'active' => $this->getId() === 'tools',
                            ),
                            array('label' => Yii::t('backend', 'Pages'),
                                'url' => array('/page'),
                                'visible' => user()->checkAccess('Page.Admin'),
                                'active' => $this->getId() === 'page',
                            ),
                            array('label' => Yii::t('backend', 'About'),
                                'url' => array('/about'),
                                'visible' => user()->checkAccess('About'),
                                'active' => $this->getId() === 'about',
                            ),
                            array('label' => Yii::t('backend', 'Replies'),
                                'url' => array('/reply'),
                                'visible' => user()->checkAccess('Reply'),
                                'active' => $this->getId() === 'reply',
                            ),

                        )
                    ),
                    array('label' => Yii::t('backend', 'Shop'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Literal.*'),
                        'active' => $this->getId() === 'literal',
                        'items' => array(
                            array('label' => Yii::t('backend', 'Delivery'),
                                'url' => array('/delivery'),
                                'visible' => user()->checkAccess('Delivery.*'),
                                'active' => $this->getId() === 'delivery',
                            ),
                            array('label' => Yii::t('backend', 'Delivery place'),
                                'url' => array('/deliveryplace'),
                                'visible' => user()->checkAccess('Deliveryplace.*'),
                                'active' => $this->getId() === 'deliveryplace',
                            ),
                            array('label' => Yii::t('backend', 'Faq'),
                                'url' => array('/faq'),
                                'visible' => user()->checkAccess('Faq.*'),
                                'active' => $this->getId() === 'faq',
                            ),
                            array('label' => Yii::t('backend', 'Discount'),
                                'url' => array('/discount'),
                                'visible' => user()->checkAccess('Discount.*'),
                                'active' => $this->getId() === 'discount',
                            ),
                            array('label' => Yii::t('backend', 'Paytype'),
                                'url' => array('/paytype'),
                                'visible' => user()->checkAccess('Paytype.*'),
                                'active' => $this->getId() === 'paytype',
                            ),
                            array('label' => Yii::t('backend', 'Teaser'),
                                'url' => array('/teaser'),
                                'visible' => user()->checkAccess('Teaser.*'),
                                'active' => $this->getId() === 'teaser',
                            ),
                             array('label' => Yii::t('backend', 'Partner'),
                                'url' => array('/partner'),
                                'visible' => user()->checkAccess('Partner.*'),
                                'active' => $this->getId() === 'partner',
                            ),
                             array('label' => Yii::t('backend', 'Press'),
                                'url' => array('/press'),
                                'visible' => user()->checkAccess('Press.*'),
                                'active' => $this->getId() === 'press',
                            ),
                            array('label' => Yii::t('backend', 'Comments'),
                                'url' => array('/comments'),
                                'visible' => user()->checkAccess('Comments.Admin'),
                                'active' => $this->getId() === 'comments',
                            ),
                            array('label' => Yii::t('backend', 'Actions'),
                                'url' => array('/action'),
                                'visible' => user()->checkAccess('Action.*'),
                                'active' => $this->getId() === 'action',
                            ),
                             array('label' => Yii::t('backend', 'Order'),
                                'url' => array('/order'),
                                'visible' => user()->checkAccess('Order.*'),
                                'active' => $this->getId() === 'order',
                            ),
                            array('label' => Yii::t('backend', 'Charity'),
                                'url' => array('/charity'),
                                'visible' => user()->checkAccess('Charity.*'),
                                'active' => $this->getId() === 'charity',
                            ),
                        )
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