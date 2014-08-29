<?php
/**
 * This is the model class for table "{{drink_dish}}".
 *
 * The followings are the available columns in table '{{drink_dish}}':
 * @property integer $id
 * @property integer $dish_id
 * @property integer $drink_id
 *
 * @method DrinkDish active
 * @method DrinkDish cache($duration = null, $dependency = null, $queryCount = 1)
 * @method DrinkDish indexed($column = 'id')
 * @method DrinkDish language($lang = null)
 * @method DrinkDish select($columns = '*')
 * @method DrinkDish limit($limit, $offset = 0)
 * @method DrinkDish sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dish $dish
 * @property Drink $drink
 */
class DrinkDish extends BaseActiveRecord
{

    
	public function updateForDrink($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
       
        foreach($newData as $item)
					if((int)$item['drink_id']>0)
                    $buff[(int)$item['drink_id']] = $item['drink_id'];
        $newData = $buff;
		
		if(empty($newData))
            return self::model()->deleteAllByAttributes(array('dish_id' => $id));
		
        

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('dish_id' => $id));
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
	            //$item->value = (int)$newData[$item['drink_id']];
	            //$item->update(array('value', ));
	            unset($newData[$item['drink_id']]);
	            ++$o;
			}
        }

        // delete info
        self::model()->deleteAllByAttributes(array('dish_id' => $id, 'drink_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $drink_id => $value)
        {
            $model->dish_id = $id;
            $model->drink_id = $drink_id;
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
     * @return DrinkDish the static model class
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
        return '{{drink_dish}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id', 'required'),
            array('dish_id, drink_id', 'numerical', 'integerOnly' => true),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
            array('drink_id', 'exist', 'className' => 'Drink', 'attributeName' => 'id'),
        
            array('id, dish_id, drink_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'dish' => array(self::BELONGS_TO, 'Dish', 'dish_id'),
            'drink' => array(self::BELONGS_TO, 'Drink', 'drink_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'dish_id' => Yii::t('backend', 'Dish'),
            'drink_id' => Yii::t('backend', 'Drink'),
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
		$criteria->compare('t.dish_id',$this->dish_id);
		$criteria->compare('t.drink_id',$this->drink_id);

		$criteria->with = array('dish', 'drink');

        return parent::searchInit($criteria);
    }
    public function getDrinks($params=array()){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          gs_drink.id,
          gs_drink.title,
          gs_drink.`detail_text`,
          gs_drink.`price`,
          gs_drink_dish.`drink_id`,
          IF(gs_file.id,CONCAT("/",gs_file.path,"/",gs_file.`file`),"") AS image
        FROM
          `gs_drink_dish`
        INNER JOIN gs_drink
        ON gs_drink.id=gs_drink_dish.`drink_id` AND gs_drink.status=1
        LEFT JOIN gs_file
        ON gs_drink.`image_id`=gs_file.id
        WHERE gs_drink_dish.`dish_id` IN ('.implode(',',$params['dishes']).')
        AND gs_drink.`status`=1
        GROUP BY gs_drink.id
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if($result)
            return $result;
    }
}