<?php
/**
 * This is the model class for table "{{order_total}}".
 *
 * The followings are the available columns in table '{{order_total}}':
 * @property integer $id
 * @property integer $order_id
 * @property string $code
 * @property string $title
 * @property string $value
 *
 * @method OrderTotal active
 * @method OrderTotal cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OrderTotal indexed($column = 'id')
 * @method OrderTotal language($lang = null)
 * @method OrderTotal select($columns = '*')
 * @method OrderTotal limit($limit, $offset = 0)
 * @method OrderTotal sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Order $order
 */
class OrderTotal extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return OrderTotal the static model class
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
        return '{{order_total}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('order_id, code', 'required'),
            array('order_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 32),
            array('title', 'length', 'max' => 256),
            array('value', 'length', 'max' => 15),
            array('order_id', 'exist', 'className' => 'Order', 'attributeName' => 'id'),

            array('id, order_id, code, title, value', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'order_id' => Yii::t('backend', 'Order'),
            'code' => Yii::t('backend', 'Code'),
            'title' => Yii::t('backend', 'Title'),
            'value' => Yii::t('backend', 'Value'),
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
		$criteria->compare('t.order_id',$this->order_id);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.value',$this->value,true);

		$criteria->with = array('order');

        return parent::searchInit($criteria);
    }
}