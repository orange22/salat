<?php
/**
 * This is the model class for table "{{drink}}".
 *
 * The followings are the available columns in table '{{drink}}':
 * @property integer $id
 * @property string $title
 * @property string $detail_text
 * @property integer $image_id
 * @property integer $status
 * @property integer $sort
 * @property double $price
 *
 * @method Drink active
 * @method Drink cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Drink indexed($column = 'id')
 * @method Drink language($lang = null)
 * @method Drink select($columns = '*')
 * @method Drink limit($limit, $offset = 0)
 * @method Drink sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 * @property DrinkDish[] $drinkDishes
 */
class Drink extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Drink the static model class
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
        return '{{drink}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('title', 'length', 'max' => 255),
            array('detail_text, image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, detail_text, image_id, status, sort, price', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'drinkDishes' => array(self::HAS_MANY, 'DrinkDish', 'drink_id'),
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
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'image_id' => Yii::t('backend', 'Image'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'price' => Yii::t('backend', 'Price'),
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
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.price',$this->price);

        return parent::searchInit($criteria);
    }
}