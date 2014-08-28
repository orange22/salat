<?php
/**
 * Base class for language models
 *
 * @property int $pid
 * @property string $language_id
 */
abstract class LangActiveRecord extends BaseActiveRecord
{
    public function getIdAttr()
    {
        return 'pid';
    }

    /**
     * List of items
     *
     * @param array $filterKeys Select only specified keys
     * @param string $lang Specific language
     * @return array
     */
    public function listData($filterKeys = array(), $lang = null)
    {
        $data = $this->language($lang)->active()->sort();
        if($filterKeys)
            $data = $data->findAllByAttributes(array('pid' => $filterKeys));
        else
            $data = $data->findAll();
        $this->resetScope();

        return CHtml::listData((array)$data, 'pid', 'title');
    }

    /**
     * Get all active keys
     *
     * @param array $filterKeys Select only specified keys
     * @param string $lang Specific language
     * @return array
     */
    public function fetchKeys($filterKeys = array(), $lang = null)
    {
        $where = array('and', 'status = 1', array('and', 'language_id = :lang', '1=1'));
        if($filterKeys)
            $where[2][2] = array('in', 'pid', $filterKeys);

        $data = app()->db
            ->cache(param('cacheDuration'), new TagCacheDependency($this->classId()))
            ->createCommand()
            ->select('GROUP_CONCAT(pid)')
            ->from($this->tableName())
            ->where($where)
            ->queryScalar(array(
            ':lang' => (!$lang ? Yii::app()->language : $lang)
        ));

        return explode(',', $data);
    }

    /**
     * PID scope
     *
     * @param int $pid Model PID
     * @return LangActiveRecord
     */
    public function pid($pid)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $this->getTableAlias().".pid = :pid",
            'params' => array(':pid' => $pid)
        ));

        return $this;
    }

    /**
     * Attributes same for all languages
     *
     * @return array
     */
    public function fixedAttributes()
    {
        return array('pid');
    }

    /**
     * Convert attribute array of errors to string
     * Rid of multidimensional errors array
     *
     * @return array Array of attributes errors [label: error, lang: [label: error]]
     */
    public function stringifyAttributeErrors()
    {
        $o = array('fixed' => array(), 'lang' => array());
        $lang = Language::getTitle($this->language_id, false);
        $fixedAttr = $this->fixedAttributes();
        foreach($this->getErrors() as $attr => $errors)
        {
            $isFixed = in_array($attr, $fixedAttr);

            $label = $this->getAttributeLabel($attr);
            if(count($errors) > 1)
                $tmp = '<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
            else
                $tmp = is_array($errors) ? $errors[0] : $errors;

            if($isFixed)
                $o['fixed'][$label] = $tmp;
            else
                $o['lang'][$lang][$label] = $tmp;
        }

        return $o;
    }

    /**
     * Get bounded PID
     *
     * @return int
     */
    public function getBoundedPid()
    {
        // polymorphic association
        if(array_key_exists('bpid', $this->relations()))
            return $this->pid;

        return $this->getPrimaryKey();
    }

    /**
     * Indexed scope
     *
     * @param string $col
     * @return LangActiveRecord
     */
    public function indexed($col = 'language_id')
    {
        return parent::indexed($col);
    }

    /**
     * Language scope
     *
     * @param string $lang Language
     * @return LangActiveRecord
     */
    public function language($lang = null)
    {
        if(!$lang)
            $lang = Yii::app()->language;

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $this->getTableAlias().".language_id = :lang",
            'params' => array(':lang' => $lang)
        ));

        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
        );
    }

    public function rules()
    {
        return array(
            array('language_id', 'required'),
            array('pid', 'required', 'on' => 'update'),
            array('pid', 'numerical', 'integerOnly' => true),
            array('language_id', 'exist', 'attributeName' => 'id', 'className' => 'Language'),
        );
    }

    public function deleteBasePid($pid)
    {
        return app()->db->createCommand('DELETE FROM {{base}} WHERE pid = :pid')->execute(array(':pid' => $pid));
    }

    protected function afterSave()
    {
        $this->bindPid();

        parent::afterSave();
    }

    public function beforeSave()
    {
        $this->bindPid();

        foreach($this->fixedAttributes() as $attr)
        {
            if($this->getAttribute($attr) === '')
                $this->setAttribute($attr, null);
        }

        return parent::beforeSave();
    }

    protected function afterDelete()
    {
        $this->deleteBasePid($this->pid);

        parent::afterDelete();
    }


    /**
     * Search initialization
     *
     * @param CDbCriteria $criteria
     * @return CActiveDataProvider
     */
    protected function searchInit($criteria = null)
    {
        if(!$criteria)
            $criteria = new CDbCriteria;

        if(!$this->language_id)
            $this->language_id = Language::getDefault();

        $criteria->compare('t.language_id', $this->language_id);

        return parent::searchInit($criteria);
    }

    /**
     * Bind PID to model
     *
     * @return int Bounded PID
     */
    protected function bindPid()
    {
        if($this->pid)
            return $this->pid;

        // polymorphic association
        if(array_key_exists('bpid', $this->relations()))
        {
            app()->db->createCommand('INSERT INTO {{base}} VALUES(NULL)')->execute();
            $this->pid = app()->db->lastInsertID;

            return $this->pid;
        }

        $this->updateByPk($this->getPrimaryKey(), array(
            'pid' => $this->getPrimaryKey(),
        ));
        $this->pid = $this->getPrimaryKey();

        return $this->pid;
    }
}
