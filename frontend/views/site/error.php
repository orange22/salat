<?php
/**
 * error.php
 *
 * General view file to display error messages
 * Change to suit your needs.
 *
 * @see errHandler at the main.php configuration file
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/22/12
 * Time: 8:27 PM
 */
$this->pageTitle .= ' - Error';
?>
        <div id="main"><!--main start-->
            <div id="content">
                <div class="content-box">
                    <h1>Error <?php echo $code ?></h1>
                    <div class="content-block">
                            <p><?php echo CHtml::encode($message) ?></p>
                    </div>
                </div>
            </div>
            <?//$this->renderWidgets($page->id);?>
        <div class="see-menu btn-holder center">
            <a href="/#top" class="green-btn">
                <span><?=Yii::t('frontend', 'Actualdish');?></span>
            </a>
        </div>
        </div><!--main end-->