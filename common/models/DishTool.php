<?php
/**
 * This is the model class for table "{{dish_tool}}".
 *
 * The followings are the available columns in table '{{dish_tool}}':
 * @property integer $id
 * @property integer $dish_id
 * @property integer $tool_id
 * @property integer $sort
 *
 * @method DishTool active
 * @method DishTool cache($duration = null, $dependency = null, $queryCount = 1)
 * @method DishTool indexed($column = 'id')
 * @method DishTool language($lang = null)
 * @method DishTool select($columns = '*')
 * @method DishTool limit($limit, $offset = 0)
 * @method DishTool sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dish $tool
 * @property Dish $dish
 */
class DishTool extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return DishTool the static model class
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
        return '{{dish_tool}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id, tool_id', 'required'),
            array('dish_id, tool_id, sort', 'numerical', 'integerOnly' => true),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
            array('tool_id', 'exist', 'className' => 'Tools', 'attributeName' => 'id'),
        
            array('id, dish_id, tool_id, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'tool' => array(self::BELONGS_TO, 'Dish', 'tool_id'),
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
            'tool_id' => Yii::t('backend', 'Tool'),
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
		$criteria->compare('t.tool_id',$this->tool_id);
		$criteria->compare('t.sort',$this->sort);

		$criteria->with = array('tool', 'dish');

        return parent::searchInit($criteria);
    }
    public function updateForTool($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
        foreach($newData as $item)
            if((int)$item['tool_id']>0)
                $buff[(int)$item['tool_id']] = $item['tool_id'];
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
            if(!isset($newData[$item['tool_id']]))
            {
                $delete[] = $item['tool_id'];
                continue;
            }
            if((int)$newData[$item['tool_id']]>0){
                unset($newData[$item['tool_id']]);
                ++$o;
            }
        }
        // delete info
        self::model()->deleteAllByAttributes(array('dish_id' => $id, 'tool_id' => $delete));
        // add new info
        $model = new self();
        foreach($newData as $tool_id => $value)
        {
            $model->dish_id = $id;
            $model->tool_id = $tool_id;
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