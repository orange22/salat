<?php
/**
 * This is the model class for table "{{discount}}".
 *
 * The followings are the available columns in table '{{discount}}':
 * @property integer $id
 * @property string $title
 * @property integer $discount
 * @property string $disccode
 * @property integer $user_id
 * @property integer $discounttype_id
 * @property integer $activations
 * @property string $date_end
 * @property integer $sort
 * @property integer $status
 *
 * @method Discount active
 * @method Discount cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Discount indexed($column = 'id')
 * @method Discount language($lang = null)
 * @method Discount select($columns = '*')
 * @method Discount limit($limit, $offset = 0)
 * @method Discount sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Discounttype $discounttype
 * @property User $user
 * @property Order[] $orders
 */
class Discount extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Discount the static model class
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
        return '{{discount}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, discount, disccode, user_id, discounttype_id,discountmode_id', 'required'),
            array('discount, user_id, discounttype_id,discountmode_id, activations, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('disccode', 'length', 'max' => 55),
            array('date_end', 'safe'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('discounttype_id', 'exist', 'className' => 'Discounttype', 'attributeName' => 'id'),
            array('discountmode_id', 'exist', 'className' => 'Discountmode', 'attributeName' => 'id'),
        
            array('id, title, discount, disccode, user_id, discounttype_id, discountmode_id, activations, date_end, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'discounttype' => array(self::BELONGS_TO, 'Discounttype', 'discounttype_id'),
            'discountmode' => array(self::BELONGS_TO, 'Discountmode', 'discountmode_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'orders' => array(self::HAS_MANY, 'Order', 'discount_id'),
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
            'discount' => Yii::t('backend', 'Discount'),
            'disccode' => Yii::t('backend', 'Disccode'),
            'user_id' => Yii::t('backend', 'User'),
            'discounttype_id' => Yii::t('backend', 'Discounttype'),
            'discountmode_id' => Yii::t('backend', 'Discountmode'),
            'activations' => Yii::t('backend', 'Activations'),
            'date_end' => Yii::t('backend', 'Date End'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
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
		$criteria->compare('t.discount',$this->discount);
		$criteria->compare('t.disccode',$this->disccode,true);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.discounttype_id',$this->discounttype_id);
		$criteria->compare('t.discountmode_id',$this->discountmode_id);
		$criteria->compare('t.activations',$this->activations);
		$criteria->compare('t.date_end',$this->date_end,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('discounttype','discountmode','user');

        return parent::searchInit($criteria);
    }
}