<?php
/**
 * This is the model class for table "{{dish_image}}".
 *
 * The followings are the available columns in table '{{dish_image}}':
 * @property integer $id
 * @property integer $dish_id
 * @property integer $image_id
 *
 * @method DishImage active
 * @method DishImage cache($duration = null, $dependency = null, $queryCount = 1)
 * @method DishImage indexed($column = 'id')
 * @method DishImage language($lang = null)
 * @method DishImage select($columns = '*')
 * @method DishImage limit($limit, $offset = 0)
 * @method DishImage sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 * @property Dish $dish
 */
class DishImage extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id','thumb_id'
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return DishImage the static model class
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
        return '{{dish_image}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id, image_id', 'required'),
            array('dish_id', 'numerical', 'integerOnly' => true),
            array('image_id, thumb_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
             array('image_id, thumb_id', 'safe'),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
        
            array('id, dish_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'thumb' => array(self::BELONGS_TO, 'File', 'thumb_id'),
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
            'image_id' => Yii::t('backend', 'Image'),
            'thumb_id' => Yii::t('backend', 'Thumb'),
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
		$criteria->compare('t.image_id',$this->image_id);

		$criteria->with = array('dish');

        return parent::searchInit($criteria);
    }
	public function updateSorting($dishid, $data)
    {
        parse_str($data, $parsed);

        if(empty($parsed['img']))
            return false;

        $sql = Yii::app()->db->createCommand(
            'UPDATE {{dish_image}} SET sort = :sort WHERE dish_id = :dish AND id = :id'
        );
        $sql->prepare();
        foreach($parsed['img'] as $i => $id)
            $sql->execute(array(':sort' => $i + 1, ':id' => $id, ':dish' => $dishid));

        $this->refreshCache();

        return true;
    }
}