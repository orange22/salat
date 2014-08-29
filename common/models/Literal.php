<?php
/**
 * This is the model class for table "{{literal}}".
 *
 * The followings are the available columns in table '{{literal}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property string $entity
 * @property string $title
 *
 * @method Literal active
 * @method Literal cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Literal indexed($column = 'language_id')
 * @method Literal language($lang = null)
 * @method Literal select($columns = '*')
 * @method Literal limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property Language $language
 * @property Product[] $productColors
 * @property Product[] $productCountries
 * @property Product[] $productApparels
 */
class Literal extends LangActiveRecord
{
    public function listData($ent, $filterKeys = array(), $lang = null)
    {
        $data = $this->language($lang)->ent($ent)->sort();
        if($filterKeys)
            $data = $data->findAllByAttributes(array('pid' => $filterKeys));
        else
            $data = $data->findAll();
        $this->resetScope();

        return CHtml::listData((array)$data, 'pid', 'title');
    }

    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'entity',
        ));
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Literal the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{literal}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('entity, title', 'required'),
            array('entity', 'length', 'max' => 10),
            array('title', 'length', 'max' => 256),

            array('id, pid, language_id, entity, title', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
            'productCountries' => array(self::HAS_MANY, 'Product', array('language_id' => 'language_id', 'country_pid' => 'pid')),
            'productColors' => array(self::HAS_MANY, 'Product', array('language_id' => 'language_id', 'color_pid' => 'pid')),
            'productApparels' => array(self::HAS_MANY, 'Product', array('language_id' => 'language_id', 'apparel_pid' => 'pid')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => 'PID',
            'language_id' => Yii::t('backend', 'Language'),
            'entity' => Yii::t('backend', 'Entity'),
            'title' => Yii::t('backend', 'Title'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $this->entity = app()->controller->getId();

        $criteria->compare('t.id',$this->id);
		$criteria->compare('t.pid',$this->pid);
		$criteria->compare('t.entity',$this->entity);
		$criteria->compare('t.title',$this->title,true);

        return parent::searchInit($criteria);
    }

    /**
     * Entity scope
     *
     * @param string $entity Entity name
     * @return \Literal
     */
    public function ent($entity)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $this->getTableAlias().".entity = :ent",
            'params' => array(':ent' => $entity)
        ));

        return $this;
    }

    public function sort($columns = 'title')
    {
        return parent::sort($columns);
    }
}