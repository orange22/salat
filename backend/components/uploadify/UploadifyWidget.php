<?php
/**
 * Uploadify widget
 * Based on EUploadiffyWidget
 */
class UploadifyWidget extends CInputWidget
{
    /**
     * @var array the uploadify package.
     * Defaults to array(
     *     'basePath'=>dirname(__FILE__).'/vendors/jquery.uploadify-v3.1',
     *     'js'=>array('jquery.uploadify'.(YII_DEBUG?'':'.min').'.js'),
     *     'css'=>array('css/uploadify.css'),
     *     'depends'=>array('jquery'),
     * )
     * @see   CClientScript::$packages
     * @since 1.7
     */
    public $package = array();

    /**
     * @var string|null the name of the POST parameter where save session id.
     *      Or null to disable sending session id. Use {@link EForgerySessionFilter} to load session by id from POST.
     *      Defaults to null.
     * @see EForgerySessionFilter
     */
    public $sessionParam;

    /**
     * @var array extension options. For more info read {@link http://www.uploadify.com/documentation/ documentation}
     */
    public $options = array();

    /**
     * Init widget.
     */
    public function init()
    {
        list($this->name, $this->id) = $this->resolveNameId();
        // Set defaults package.
        if($this->package == array())
        {
            $this->package = array(
                'basePath'=> dirname(__FILE__).'/vendors/jquery.uploadify-v3.2',
                'js'      => array(
                    'jquery.uploadify'.(YII_DEBUG ? '' : '.min').'.js',
                ),
                'css'     => array(
                    'css/uploadify.css',
                ),
                'depends' => array(
                    'jquery',
                ),
            );
        }

        // Publish package assets. Force copy assets in debug mode.
        $assets = Yii::app()->getAssetManager()->publish($this->package['basePath'], false, -1, YII_DEBUG);
        $this->package['baseUrl'] = $assets;

        if(!isset($this->options['swf']))
            $this->options['swf'] = $assets.'/uploadify.swf';

        if(!isset($this->options['cancelImg']))
            $this->options['cancelImg'] = $assets.'/uploadify-cancel.png';

        if(!isset($this->options['expressInstall']))
            $this->options['expressInstall'] = $assets.'/expressInstall.swf';

        if(!isset($this->options['uploader']))
            $this->options['uploader'] = $assets.'/uploadify.php';

        // Send session ID with via POST.
        if($this->sessionParam !== null)
            $this->options['formData'][$this->sessionParam] = Yii::app()->getSession()->getSessionId();

        if(Yii::app()->getRequest()->enableCsrfValidation && (!isset($this->options['method']) || $this->options['method'] === 'POST'))
            $this->options['formData'][Yii::app()->getRequest()->csrfTokenName] = Yii::app()->getRequest()->getCsrfToken();

        // fileDesc is required if fileExt set.
        if(!empty($this->options['fileExt']) && empty($this->options['fileDesc']))
            $this->options['fileDesc'] = Yii::t('backend', 'Supported files ({fileExt})', array('{fileExt}' => $this->options['fileExt']));

        // Generate fileDataName for linked with model attribute.
        $this->options['fileDataName'] = $this->name;

        $this->registerClientScript();
    }

    /**
     * Run widget.
     */
    public function run()
    {
        if($this->hasModel())
            echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo CHtml::fileField($this->name, $this->value, $this->htmlOptions);
    }

    /**
     * @return void
     * Register CSS and Script.
     */
    protected function registerClientScript()
    {
        $cs                        = Yii::app()->getClientScript();
        $cs->packages['uploadify'] = $this->package;
        $cs->registerPackage('uploadify');
        $cs->registerScript(
            __CLASS__.'#'.$this->id,
            'jQuery("#'.$this->id.'").uploadify('.CJavaScript::encode($this->options).');', CClientScript::POS_READY
        );
    }
}
