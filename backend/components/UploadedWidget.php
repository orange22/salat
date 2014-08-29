<?php
/**
 * UploadDeleteWidget
 * Widget shows uploaded image/file and provide checkbox to delete them
 */
class UploadedWidget extends CInputWidget
{
    /**
     * Image alt attribute value or file link text
     *
     * @var string
     */
    public $altTitle = '';

    /**
     * Prefix for delete checkbox name
     *
     * @var string
     */
    public $deletePrefix = '';

    /**
     * HTML options for image/file input
     *
     * @var array
     */
    public $htmlOptions = array();

    /**
     * Input language
     *
     * @var string
     */
    public $language = '';

    /**
     * Model related property
     *
     * @var File
     */
    public $related = null;

    public function init()
    {
        if(!$this->deletePrefix)
            $this->deletePrefix = 'del_'.get_class($this->model).'_';

        if($this->language)
            $this->deletePrefix .= $this->language.'_';
    }

    public function run()
    {
        if(!$this->model->getAttribute($this->attribute))
        {
            return '';
        }

        ?>
        <div class="control-group">
            <div class="controls">
                <?php if($file = $this->getFile()): ?>
                    <?php echo $file; ?>
                <label class="checkbox" for="<?php echo $this->deletePrefix.$this->attribute; ?>">
                    <?php echo CHtml::checkBox($this->deletePrefix.$this->attribute); ?>
                    <?php echo Yii::t('backend', 'Delete'); ?>
                </label>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get image/file tag
     *
     * @return string
     */
    protected function getFile()
    {
        /** @var $obj File */
        if(!$this->related)
            $obj = File::model()->findByPk($this->model->{$this->attribute});
        else
            $obj = $this->model->{$this->related};

        if(!$obj || !($obj instanceof File))
            return '';

        if($obj->getIsImage())
        {
            if(!isset($this->htmlOptions['width']))
            {
                $this->htmlOptions['width'] = 100;
            }

            return File::htmlLinkImage($obj, $this->altTitle, $this->htmlOptions);
        }

        return File::htmlLinkFile($obj, $this->altTitle);
    }
}