<?php
/**
 * This is the model class for table "{{course_ingredient}}".
 *
 * The followings are the available columns in table '{{course_ingredient}}':
 * @property integer $id
 * @property integer $course_id
 * @property integer $ingredient_id
 * @property integer $value
 *
 * @method CourseIngredient active
 * @method CourseIngredient cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CourseIngredient indexed($column = 'id')
 * @method CourseIngredient language($lang = null)
 * @method CourseIngredient select($columns = '*')
 * @method CourseIngredient limit($limit, $offset = 0)
 * @method CourseIngredient sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Course $course
 * @property Ingredient $ingredient
 */
class CourseIngredient extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CourseIngredient the static model class
     */
     
     public function updateForCourse($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
       
        foreach($newData as $item)
					if((int)$item['value']>0)
                    $buff[(int)$item['ingredient_id']] = $item['value'];
        
		$newData = $buff;
		
		if(empty($newData))
            return self::model()->deleteAllByAttributes(array('course_id' => $id));
		
        

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('course_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['ingredient_id']]))
            {
                $delete[] = $item['ingredient_id'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['quantity'] === (int)$item->quantity && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/
            
            if((int)$newData[$item['ingredient_id']]>0){
	            $item->value = (int)$newData[$item['ingredient_id']];
	            $item->update(array('value', ));
	            unset($newData[$item['ingredient_id']]);
	            ++$o;
			}
        }

        // delete info
        self::model()->deleteAllByAttributes(array('course_id' => $id, 'ingredient_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $ingredient_id => $value)
        {
            $model->course_id = $id;
            $model->ingredient_id = $ingredient_id;
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
     
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{course_ingredient}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('course_id, ingredient_id', 'required'),
            array('course_id, ingredient_id, value', 'numerical', 'integerOnly' => true),
            array('course_id', 'exist', 'className' => 'Course', 'attributeName' => 'id'),
            array('ingredient_id', 'exist', 'className' => 'Ingredient', 'attributeName' => 'id'),
        
            array('id, course_id, ingredient_id, value', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
            'ingredient' => array(self::BELONGS_TO, 'Ingredient', 'ingredient_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'course_id' => Yii::t('backend', 'Course'),
            'ingredient_id' => Yii::t('backend', 'Ingredient'),
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
		$criteria->compare('t.course_id',$this->course_id);
		$criteria->compare('t.ingredient_id',$this->ingredient_id);
		$criteria->compare('t.value',$this->value);

		$criteria->with = array('course', 'ingredient');

        return parent::searchInit($criteria);
    }
}