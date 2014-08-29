<?php
/**
 * This is the model class for table "{{portion}}".
 *
 * The followings are the available columns in table '{{portion}}':
 * @property integer $id
 * @property integer $dish_id
 * @property integer $value
 *
 * @method Portion active
 * @method Portion cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Portion indexed($column = 'id')
 * @method Portion language($lang = null)
 * @method Portion select($columns = '*')
 * @method Portion limit($limit, $offset = 0)
 * @method Portion sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dish $dish
 */
class Portion extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Portion the static model class
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
        return '{{portion}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id', 'required'),
            array('dish_id, value', 'numerical', 'integerOnly' => true),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
        
            array('id, dish_id, value', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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
		$criteria->compare('t.dish_id',$this->dish_id);
		$criteria->compare('t.value',$this->value);

		$criteria->with = array('dish');

        return parent::searchInit($criteria);
    }
	public function updateForPortion($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
       
        foreach($newData as $item)
					if((int)$item['value']>0)
                    $buff[(int)$item['value']] = $item['value'];
        
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
            if(!isset($newData[$item['value']]))
            {
                $delete[] = $item['value'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['value'] === (int)$item->value && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/
            
            
            if((int)$newData[$item['value']]>0){
                            $item->value = (int)$newData[$item['value']];
                            $item->update(array('value', ));
                            unset($newData[$item['value']]);
                            ++$o;
                        }
            
        }

        // delete info
        self::model()->deleteAllByAttributes(array('dish_id' => $id, 'value' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $k => $value)
        {
            $model->dish_id = $id;
            $model->value = $value;
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