<?php
/**
 * This is the model class for table "{{order_drink}}".
 *
 * The followings are the available columns in table '{{order_drink}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $drink_id
 * @property string $quantity
 *
 * @method OrderDrink active
 * @method OrderDrink cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OrderDrink indexed($column = 'id')
 * @method OrderDrink language($lang = null)
 * @method OrderDrink select($columns = '*')
 * @method OrderDrink limit($limit, $offset = 0)
 * @method OrderDrink sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Drink $drink
 * @property Order $order
 */
class OrderDrink extends BaseActiveRecord
{

    
	 public function updateForOrder($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size quantitys, use last one
       
        foreach($newData as $item)
					if((int)$item['quantity']>0)
                    $buff[(int)$item['drink_id']] = $item['quantity'];
        
		$newData = $buff;
		
		if(empty($newData))
            return self::model()->deleteAllByAttributes(array('order_id' => $id));
		
        

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('order_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['drink_id']]))
            {
                $delete[] = $item['drink_id'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['quantity'] === (int)$item->quantity && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/
            
            if((int)$newData[$item['drink_id']]>0){
	            $item->quantity = (int)$newData[$item['drink_id']];
	            $item->update(array('quantity', ));
	            unset($newData[$item['drink_id']]);
	            ++$o;
			}
        }

        // delete info
        self::model()->deleteAllByAttributes(array('order_id' => $id, 'drink_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $drink_id => $quantity)
        {
            $model->order_id = $id;
            $model->drink_id = $drink_id;
            $model->quantity = $quantity;
            if($model->save(false))
            {
                ++$o;
                $model->id = null;
                $model->setIsNewRecord(true);
            }
        }

        return $o;
    } 
	
	
	 /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return OrderDrink the static model class
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
        return '{{order_drink}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('order_id, drink_id, quantity', 'required'),
            array('order_id, drink_id', 'numerical', 'integerOnly' => true),
            array('quantity', 'length', 'max' => 10),
            array('order_id', 'exist', 'className' => 'Order', 'attributeName' => 'id'),
            array('drink_id', 'exist', 'className' => 'Drink', 'attributeName' => 'id'),
        
            array('id, order_id, drink_id, quantity', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'drink' => array(self::BELONGS_TO, 'Drink', 'drink_id'),
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
            'drink_id' => Yii::t('backend', 'Drink'),
            'quantity' => Yii::t('backend', 'Quantity'),
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
		$criteria->compare('t.drink_id',$this->drink_id);
		$criteria->compare('t.quantity',$this->quantity,true);

		$criteria->with = array('drink', 'order');

        return parent::searchInit($criteria);
    }
}