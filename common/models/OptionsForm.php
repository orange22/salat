<?php
class OptionsForm extends CFormModel
{
    /**
     * Option models
     *
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options Array of Option models
     * @param string $scenario
     */
    public function __construct($options = array(), $scenario = '')
    {
        foreach($options as $option)
        {
            if(!user()->checkAccess($option->role))
                continue;
            $this->options[] = $option;
        }
        parent::__construct($scenario);
    }

    public function attributeNames()
    {
        $o = array();
        foreach($this->options as $option)
            $o[] = $option->key;

        return $o;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Save options
     *
     * @param array $data Data to save [key => value]
     * @return bool
     */
    public function save($data)
    {
        foreach($this->options as $option)
        {
            /** @var $option Option */
            if(!array_key_exists($option->key, $data))
                continue;

            if(!user()->checkAccess($option->role))
            {
                $this->addError(
                    $option->title,
                    Yii::t('cp', 'You do not have enough permissions to modify "{option}".', array('{option}' => $option->title))
                );
            }

            $option->value = $data[$option->key];
            if(!$option->save())
            {
                $this->addError($option->title, $option->getError('value'));
            }
        }

        return (count($this->getErrors()) == 0);
    }

}
