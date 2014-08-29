<?php
/**
 * This is the model class for table "{{dish}}".
 *
 * The followings are the available columns in table '{{dish}}':
 * @property integer $id
 * @property string $title
 * @property string $date_create
 * @property integer $status
 * @property integer $sort
 * @property string $detail_text
 * @property integer $prepare
 * @property integer $steps
 * @property integer $dishtype_id
 *
 * @method Dish active
 * @method Dish cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Dish indexed($column = 'id')
 * @method Dish language($lang = null)
 * @method Dish select($columns = '*')
 * @method Dish limit($limit, $offset = 0)
 * @method Dish sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Dishtype $dishtype
 * @property DishImage[] $dishImages
 */
 Yii::import('common.extensions.shopping-cart.*');
class Dish extends BaseActiveRecord implements IECartPosition
{

    public function getId()
    {
       return $this->id;
    }
	
	 public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
            'e' => array('class' => 'common.models.Entity'),
            'seo' => array('class' => 'common.components.SeoBehavior'),
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id'
                ),
                'fileAttributes' => array(
                ),
            )
        ));
    }
	
    /**
     * @throws CException
     * @return float price
     */
    public function getPrice()
    {
       return $this->price;
    }
	 public function fetchCartItems($productData)
    {
    	/** @var $products Product[] */
        $products = self::model()->with('dishThumbs')->active()->indexed('id')->findAllByAttributes(array(
            'id' => array_keys($productData)
        ));
		/** @var $info ProductInfo[] */
        //$info = ProductInfo::model()->findAllByAttributes(array('id' => array_keys($infoData)));

        $o = array();
        foreach($products as $item)
        {
            /** @var $model Product */
            $model = clone $products[$item->id];
            //$model->setExactInfo($item);
            $o[] = $model;
        }

        return $o;
    }
	 /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Dish the static model class
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
        return '{{dish}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('sort,cookware_1_id,cookware_2_id,cookware_1_num,cookware_2_num,persons', 'required'),
            array('status, topsell, new, sort, prepare, steps, dishtype_id, main,cookware_1_id,cookware_2_id,cookware_1_num,cookware_2_num,persons,difficulty', 'numerical', 'integerOnly' => true),
            array('price,weight', 'numerical'),
            array('title', 'length', 'max' => 255),
            array('detail_text,video,image_id, shef_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('shef_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('dishtype_id', 'exist', 'className' => 'Dishtype', 'attributeName' => 'id'),
            array('id, title, date_create, image_id, topsell, new, status, sort, detail_text,video, prepare, steps, dishtype_id, price,weight,difficulty', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'dishtype' => array(self::BELONGS_TO, 'Dishtype', 'dishtype_id'),
            'dishImages' => array(self::HAS_MANY, 'DishImage', 'dish_id','with'=>'image','order'=>'dishImages.sort asc'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'courses' => array(self::HAS_MANY, 'Course', 'dish_id'),
            'dishThumbs' => array(self::HAS_MANY, 'DishImage', 'dish_id','with'=>'thumb','order'=>'dishThumbs.sort asc'),
            'drinkDishes' => array(self::HAS_MANY, 'DrinkDish', 'dish_id'),
            'dishSimilar' => array(self::HAS_MANY, 'DishSimilar', 'dish_id'),
            'dishTools' => array(self::HAS_MANY, 'DishTool', 'dish_id'),
            'orderDishes' => array(self::HAS_MANY, 'OrderDish', 'dish_id'),
            'cookware1' => array(self::BELONGS_TO, 'Cookware', 'cookware_1_id'),
            'cookware2' => array(self::BELONGS_TO, 'Cookware', 'cookware_2_id'),
            'persons' => array(self::BELONGS_TO, 'Persons', 'persons'),
            'portions' => array(self::HAS_MANY, 'Portion', 'dish_id'),
            'shef' => array(self::BELONGS_TO, 'User', 'shef_id'),
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
            'image_id' => Yii::t('backend', 'Recipe'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'video' => Yii::t('backend', 'Video'),
            'prepare' => Yii::t('backend', 'Prepare'),
            'steps' => Yii::t('backend', 'Steps'),
            'dishtype_id' => Yii::t('backend', 'Dishtype'),
            'price' => Yii::t('backend', 'Price'),
            'weight' => Yii::t('backend', 'Weightkg'),
            'main' => Yii::t('backend', 'Main dish'),
            'cookware_1_id' => Yii::t('backend', 'Cookware_1_id'),
            'cookware_2_id' => Yii::t('backend', 'Cookware_2_id'),
            'cookware_1_num' => Yii::t('backend', 'Cookware_1_num'),
            'cookware_2_num' => Yii::t('backend', 'Cookware_2_num'),
            'persons' => Yii::t('backend', 'Persons'),
            'difficulty' => Yii::t('backend', 'Difficulty'),
            'shef_id' => Yii::t('backend', 'Shef'),
            'new' => Yii::t('backend', 'New'),
            'topsell' => Yii::t('backend', 'Top'),
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
		$criteria->compare('t.image_id',$this->image_id);
        $criteria->compare('t.dishgroup_id',1);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.video',$this->video,true);
		$criteria->compare('t.prepare',$this->prepare);
		$criteria->compare('t.steps',$this->steps);
		$criteria->compare('t.dishtype_id',$this->dishtype_id);
		$criteria->compare('t.price',$this->price);
        $criteria->compare('t.weight',$this->weight);
		$criteria->compare('t.main',$this->main);
        $criteria->compare('t.persons',$this->persons);
        $criteria->compare('t.new',$this->new);


		$criteria->with = array('dishtype');

        return parent::searchInit($criteria);
    }
}