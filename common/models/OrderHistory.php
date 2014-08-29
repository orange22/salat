<?php
/**
 * This is the model class for table "{{order_history}}".
 *
 * The followings are the available columns in table '{{order_history}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $order_status_id
 * @property string $date_created
 *
 * @method OrderHistory active
 * @method OrderHistory cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OrderHistory indexed($column = 'id')
 * @method OrderHistory language($lang = null)
 * @method OrderHistory select($columns = '*')
 * @method OrderHistory limit($limit, $offset = 0)
 * @method OrderHistory sort($columns = '')
 *
 * The followings are the available model relations:
 * @property OrderStatus $orderStatus
 * @property Order $order
 */
class OrderHistory extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return OrderHistory the static model class
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
        return '{{order_history}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('order_id, order_status_id, date_created', 'required'),
            array('order_id, order_status_id', 'numerical', 'integerOnly' => true),
            array('order_id', 'exist', 'className' => 'Order', 'attributeName' => 'id'),
            array('order_status_id', 'exist', 'className' => 'OrderStatus', 'attributeName' => 'id'),

            array('id, order_id, order_status_id, date_created', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'orderStatus' => array(self::BELONGS_TO, 'OrderStatus', 'order_status_id'),
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
            'order_status_id' => Yii::t('backend', 'Order Status'),
            'date_created' => Yii::t('backend', 'Date Created'),
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
		$criteria->compare('t.order_status_id',$this->order_status_id);
		$criteria->compare('t.date_created',$this->date_created,true);

		$criteria->with = array('orderStatus', 'order');

        return parent::searchInit($criteria);
    }
}