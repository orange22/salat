<?php
/**
 * This is the model class for table "{{dish_similar}}".
 *
 * The followings are the available columns in table '{{dish_similar}}':
 * @property integer $id
 * @property integer $dish_id
 * @property integer $similar_id
 * @property integer $sort
 *
 * @method DishSimilar active
 * @method DishSimilar cache($duration = null, $dependency = null, $queryCount = 1)
 * @method DishSimilar indexed($column = 'id')
 * @method DishSimilar language($lang = null)
 * @method DishSimilar select($columns = '*')
 * @method DishSimilar limit($limit, $offset = 0)
 * @method DishSimilar sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dish $similar
 * @property Dish $dish
 */
class DishSimilar extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return DishSimilar the static model class
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
        return '{{dish_similar}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id, similar_id', 'required'),
            array('dish_id, similar_id, sort', 'numerical', 'integerOnly' => true),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
            array('similar_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
        
            array('id, dish_id, similar_id, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'similar' => array(self::BELONGS_TO, 'Dish', 'similar_id'),
            'dish' => array(self::BELONGS_TO, 'Dish', 'dish_id'),
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
            'similar_id' => Yii::t('backend', 'Similar'),
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
		$criteria->compare('t.dish_id',$this->dish_id);
		$criteria->compare('t.similar_id',$this->similar_id);
		$criteria->compare('t.sort',$this->sort);

		$criteria->with = array('similar', 'dish');

        return parent::searchInit($criteria);
    }
	
	public function updateForSimilar($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
       
        foreach($newData as $item)
					if((int)$item['similar_id']>0)
                    $buff[(int)$item['similar_id']] = $item['similar_id'];
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
            if(!isset($newData[$item['similar_id']]))
            {
                $delete[] = $item['similar_id'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['quantity'] === (int)$item->quantity && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/
            
            if((int)$newData[$item['similar_id']]>0){
	            //$item->value = (int)$newData[$item['similar_id']];
	            //$item->update(array('value', ));
	            unset($newData[$item['similar_id']]);
	            ++$o;
			}
        }

        // delete info
        self::model()->deleteAllByAttributes(array('dish_id' => $id, 'similar_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $similar_id => $value)
        {
            $model->dish_id = $id;
            $model->similar_id = $similar_id;
            if($model->save(false))
            {
                ++$o;
                $model->id = null;
                $model->setIsNewRecord(true);
            }
        }

        return $o;
    }  
}