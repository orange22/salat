<?php
/**
 * This is the model class for table "{{order_status}}".
 *
 * The followings are the available columns in table '{{order_status}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property string $title
 * @property integer $sort
 *
 * @method OrderStatus active
 * @method OrderStatus cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OrderStatus indexed($column = 'language_id')
 * @method OrderStatus language($lang = null)
 * @method OrderStatus select($columns = '*')
 * @method OrderStatus limit($limit, $offset = 0)
 * @method OrderStatus sort($columns = '')
 *
 * The followings are the available model relations:
 * @property OrderHistory[] $orderHistories
 * @property Language $language
 */
class OrderStatus extends LangActiveRecord
{
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
        ));
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return OrderStatus the static model class
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
        return '{{order_status}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),

            array('id, pid, language_id, title, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'orderHistories' => array(self::HAS_MANY, 'OrderHistory', 'order_status_id'),
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
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
            'title' => Yii::t('backend', 'Title'),
            'sort' => Yii::t('backend', 'Sort'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id',$this->id);
		$criteria->compare('t.pid',$this->pid);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}