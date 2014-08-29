<?php
/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $paytype_id
 * @property string $name
 * @property string $title
 * @property string $phone
 * @property string $delivery_from
 * @property string $delivery_till
 * @property string $delivery_address
 * @property string $date_create
 * @property integer $discount_id
 * @property integer $status
 * @property integer $sort
 *
 * @method Order active
 * @method Order cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Order indexed($column = 'id')
 * @method Order language($lang = null)
 * @method Order select($columns = '*')
 * @method Order limit($limit, $offset = 0)
 * @method Order sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Discount $discount
 * @property Paytype $paytype
 * @property User $user
 * @property OrderDish[] $orderDishes
 */
class Order extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public $dish_count;
    public $drink_count;
    public $order_count;
    public $delivery;
    public $date_first;
    public $date_last;
    public $dishlist;
    public $drinklist;
    public $pdf;
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('phone, orderstate_id, total', 'required'),
            array('user_id, selfdeliver, paytype_id, discount_id, status, sort, orderstate_id, mail_open', 'numerical', 'integerOnly' => true),
            array('name, title, camefrom', 'length', 'max' => 255),
            array('phone, delivery_from, delivery_till', 'length', 'max' => 55),
            array('delivery_addr, deliveryplace_id, date_create, dishes, dish_count, dishlist, drinklist', 'safe'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('paytype_id', 'exist', 'className' => 'Paytype', 'attributeName' => 'id'),
            array('discount_id', 'exist', 'className' => 'Discount', 'attributeName' => 'id'),
            array('discount_id', 'exist', 'className' => 'Discount', 'attributeName' => 'id'),
            array('deliveryplace_id', 'exist', 'className' => 'Deliveryplace', 'attributeName' => 'id'),
            
            array('id, user_id, deliveryplace_id, drinklist, camefrom, paytype_id, name, title, phone, date_create, discount_id, selfdeliver, status, sort, orderstate_id, total, dishes, dish_count, drink_count, order_count, date_first, date_last', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'discount' => array(self::BELONGS_TO, 'Discount', 'discount_id'),
            'paytype' => array(self::BELONGS_TO, 'Paytype', 'paytype_id'),
            'orderstate' => array(self::BELONGS_TO, 'Orderstate', 'orderstate_id'),
            'deliveryplace' => array(self::BELONGS_TO, 'Deliveryplace', 'deliveryplace_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'orderDishes' => array(self::HAS_MANY, 'OrderDish', 'order_id'),
            //'dish_count' => array(self::STAT, 'OrderDish', 'order_id'),
            'orderDrinks' => array(self::HAS_MANY, 'OrderDrink', 'order_id'),
            'orderCharities' => array(self::HAS_MANY, 'CharityOrder', 'order_id'),
            //'orderDrinksCount' => array(self::STAT, 'OrderDrink', 'order_id'),
            //'orderOrders' => array(self::HAS_MANY, 'Order', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => Yii::t('backend', 'User'),
            'paytype_id' => Yii::t('backend', 'Paytype'),
            'name' => Yii::t('backend', 'Name'),
            'title' => Yii::t('backend', 'Title'),
            'phone' => Yii::t('backend', 'Phone'),
            'delivery_from' => Yii::t('backend', 'Delivery From'),
            'delivery_till' => Yii::t('backend', 'Delivery To'),
            'delivery_addr' => Yii::t('backend', 'Delivery Address'),
            'delivery' => Yii::t('backend', 'Delivery'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'discount_id' => Yii::t('backend', 'Discount'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'orderstate_id' => Yii::t('backend', 'Orderstate'),
            'total' => Yii::t('backend', 'Total'),
            'order_count' => Yii::t('backend', 'Orders'),
            'dish_count' => Yii::t('backend', 'Dishes'),
            'drink_count' => Yii::t('backend', 'Drinks'),
            'dishlist' => Yii::t('backend', 'Dishlist'),
            'drinklist' => Yii::t('backend', 'Drinks'),
            'camefrom' => Yii::t('backend', 'Came from'),
            'selfdeliver' => Yii::t('backend', 'Self deliver'),
            'deliveryplace_id' => Yii::t('backend', 'Delivery place'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        if(!isset($_GET['Page_sort'])){
        $_GET['Page_sort'] = 'date_create';
        $_GET['Order_sort'] = 'date_create.desc';
        }
        
        $criteria = new CDbCriteria;
            
        #count_dishes
        $dish_table = OrderDish::model()->tableName();
        $dish_count_sql = "(select ifnull(sum(pt.quantity),0) from `".$dish_table."` as pt where pt.order_id = t.id)";
        
        #count_drinks
        $drink_table = OrderDrink::model()->tableName();
        $drink_count_sql = "(select ifnull(sum(pt2.quantity),0) from `".$drink_table."` as pt2 where pt2.order_id = t.id)";
        
        #count_orders
        $order_table = Order::model()->tableName();
        $order_count_sql = "(select count(*) from `".$order_table."` as pt3 where pt3.user_id = t.user_id)";
        
        #dishlist
        $dishlist_sql = "(SELECT GROUP_CONCAT(SUBSTRING(pt5.`title`,1,25) SEPARATOR '... ') FROM gs_order_dish as pt4 inner join gs_dish as pt5 ON pt5.id=pt4.dish_id WHERE pt4.`order_id`=t.id)";
        
        #drinklist
        $drinklist_sql = "(SELECT GROUP_CONCAT(SUBSTRING(pt7.`title`,1,25) SEPARATOR '... ') FROM gs_order_drink as pt6 inner join gs_drink as pt7 ON pt7.id=pt6.drink_id WHERE pt6.`order_id`=t.id)";
        
        
        $criteria->select = array(
        '*',
        $drinklist_sql . " as drinklist",
        $dishlist_sql . " as dishlist",
        $order_count_sql . " as order_count",
        $dish_count_sql . " as dish_count",
        $drink_count_sql . " as drink_count",
        );
        $criteria->compare($dishlist_sql, $this->dishlist,true);
        $criteria->compare($drinklist_sql, $this->drinklist,true);
        $criteria->compare($dish_count_sql, $this->dish_count);
        $criteria->compare($drink_count_sql, $this->drink_count);
        $criteria->compare($order_count_sql, $this->order_count);
        
        
        if((isset($this->date_first) && trim($this->date_first) != "") && (isset($this->date_last) && trim($this->date_last) != ""))
        $criteria->addBetweenCondition('t.date_create', ''.$this->date_first.'', ''.$this->date_last.'');
        
        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.user_id',$this->user_id);
        $criteria->compare('t.paytype_id',$this->paytype_id);
        //if(isset($this->orderstate_id))
        $criteria->compare('t.orderstate_id',$this->orderstate_id);
        $criteria->compare('t.name',$this->name,true);
        $criteria->compare('t.title',$this->title,true);
        $criteria->compare('t.phone',$this->phone,true);
        $criteria->compare('t.delivery_from',$this->delivery_from,true);
        $criteria->compare('t.delivery_till',$this->delivery_till,true);
        $criteria->compare('t.delivery_addr',$this->delivery_addr,true);
        $criteria->compare('t.date_create',$this->date_create,true);
        $criteria->compare('t.discount_id',$this->discount_id);
        $criteria->compare('t.status',$this->status);
        $criteria->compare('t.selfdeliver',$this->selfdeliver);
        $criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.camefrom',$this->camefrom);
        $criteria->compare('t.deliveryplace_id',$this->deliveryplace_id);
		
        
        //$criteria->join('innse JOIN users ON users.id=authorID');     

        $criteria->with = array('discount', 'paytype', 'user', 'orderCharities');

        return parent::searchInit($criteria);
    }
	public function beforeSave(){
			if(strlen(trim($this->camefrom))>0){
				$user=User::model()->findByPk($this->user_id);
				$user->camefrom=$this->camefrom;
				$user->save();
			}else{
				$user=User::model()->findByPk($this->user_id);
				if(strlen(trim($user->camefrom))>0)
				$this->camefrom=$user->camefrom;
				
			}
			$this->phone=preg_replace('/(\W*)/', '', $this->phone);
			return parent::beforeSave();
	}
	
}