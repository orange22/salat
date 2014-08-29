<?php
/**
 * This is the model class for table "{{order_product}}".
 *
 * The followings are the available columns in table '{{order_product}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_pid
 * @property string $title
 * @property string $code
 * @property integer $quantity
 * @property string $price
 * @property string $discount
 * @property string $size
 *
 * @method OrderProduct active
 * @method OrderProduct cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OrderProduct indexed($column = 'language_id')
 * @method OrderProduct language($lang = null)
 * @method OrderProduct select($columns = '*')
 * @method OrderProduct limit($limit, $offset = 0)
 * @method OrderProduct sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Order $order
 */
class OrderProduct extends LangActiveRecord
{
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'order_id',
            'product_pid',
            'quantity',
            'price',
            'discount',
            'size',
        ));
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return OrderProduct the static model class
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
        return '{{order_product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('order_id', 'required'),
            array('order_id, product_pid, quantity', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),
            array('price, discount', 'length', 'max' => 15),
            array('size', 'length', 'max' => 6),
            array('code', 'safe'),
            array('order_id', 'exist', 'className' => 'Order', 'attributeName' => 'id'),
            array('product_pid', 'exist', 'className' => 'Product', 'attributeName' => 'pid'),

            array('id, order_id, product_pid, title, code, quantity, price, discount, size', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', array('language_id' => 'language_id', 'product_pid' => 'pid')),
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
            'product_pid' => Yii::t('backend', 'Product'),
            'title' => Yii::t('backend', 'Title'),
            'code' => Yii::t('backend', 'Code'),
            'quantity' => Yii::t('backend', 'Quantity'),
            'price' => Yii::t('backend', 'Price'),
            'discount' => Yii::t('backend', 'Discount'),
            'size' => Yii::t('backend', 'Size'),
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
		$criteria->compare('t.product_pid',$this->product_pid);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.quantity',$this->quantity);
		$criteria->compare('t.price',$this->price,true);
		$criteria->compare('t.discount',$this->discount,true);
		$criteria->compare('t.size',$this->size,true);

		$criteria->with = array('product', 'order');

        return parent::searchInit($criteria);
    }
}