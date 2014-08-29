<?php
/**
 * This is the model class for table "{{course}}".
 *
 * The followings are the available columns in table '{{course}}':
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 * @property integer $image_id
 * @property integer $calories
 * @property integer $dishtype_id
 * @property integer $dish_id
 *
 * @method Course active
 * @method Course cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Course indexed($column = 'id')
 * @method Course language($lang = null)
 * @method Course select($columns = '*')
 * @method Course limit($limit, $offset = 0)
 * @method Course sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dishtype $dishtype
 * @property Dish $dish
 * @property File $image
 * @property CourseIngredient[] $courseIngredients
 */
class Course extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'e' => array('class' => 'common.models.Entity'),
            'seo' => array('class' => 'common.components.SeoBehavior'),
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id','recipeimage_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Course the static model class
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
        return '{{course}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('dish_id', 'required'),
            array('sort,status,dishtype_id,dish_id,weight,recipe', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('image_id,recipeimage_id,calories,preview_text,detail_text', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('recipeimage_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('dishtype_id', 'exist', 'className' => 'Dishtype', 'attributeName' => 'id'),
            array('dish_id', 'exist', 'className' => 'Dish', 'attributeName' => 'id'),
        
            array('id, title, sort, status, image_id,recipeimage_id, calories, dishtype_id, dish_id,weight,recipe', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'coursetype' => array(self::BELONGS_TO, 'Dishtype', 'dishtype_id'),
            'dish' => array(self::BELONGS_TO, 'Dish', 'dish_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'recipeimage' => array(self::BELONGS_TO, 'File', 'recipeimage_id'),
            'courseIngredients' => array(self::HAS_MANY, 'CourseIngredient', 'course_id'),
            'steplist' => array(self::HAS_MANY, 'Step', 'course_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('backend', 'Title'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'image_id' => Yii::t('backend', 'Image'),
            'recipeimage_id' => Yii::t('backend', 'Recipe'),
            'calories' => Yii::t('backend', 'Calories'),
            'dishtype_id' => Yii::t('backend', 'Dishtype'),
            'dish_id' => Yii::t('backend', 'Dish'),
            'recipe' => Yii::t('backend', 'Recipe'),
            'weight' => Yii::t('backend', 'Weight'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'preview_text' => Yii::t('backend', 'Preview Text'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('t.image_id',$this->image_id);
        $criteria->compare('t.recipeimage_id',$this->recipeimage_id);
        $criteria->compare('t.recipe',$this->recipe);
		$criteria->compare('t.calories',$this->calories);
		$criteria->compare('t.dishtype_id',$this->dishtype_id);
		$criteria->compare('t.dish_id',$this->dish_id);

		$criteria->with = array('coursetype', 'dish');

        return parent::searchInit($criteria);
    }
}