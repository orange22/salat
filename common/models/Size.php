<?php
/**
 * This is the model class for table "{{size}}".
 *
 * The followings are the available columns in table '{{size}}':
 * @property integer $id
 * @property string $value
 * @property string $gender
 *
 * @method Size active
 * @method Size cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Size indexed($column = 'id')
 * @method Size language($lang = null)
 * @method Size select($columns = '*')
 * @method Size limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property ProductSize[] $productSizes
 * @property Subscription[] $subscriptionsShoe
 * @property Subscription[] $subscriptionsCloth
 */
class Size extends BaseActiveRecord
{
    public function getTitleAttr()
    {
        return 'value';
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Size the static model class
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
        return '{{size}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('value', 'required'),
            array('value', 'length', 'max' => 6),
            array('gender', 'length', 'max' => 1),

            array('id, value, gender', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'productSizes' => array(self::HAS_MANY, 'ProductSize', 'size_id'),
            'subscriptionsShoe' => array(self::HAS_MANY, 'Subscription', 'shoe_size_id'),
            'subscriptionsCloth' => array(self::HAS_MANY, 'Subscription', 'cloth_size_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'value' => Yii::t('backend', 'Value'),
            'gender' => Yii::t('backend', 'Gender'),
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
		$criteria->compare('t.value',$this->value,true);
		$criteria->compare('t.gender',$this->gender,true);

        return parent::searchInit($criteria);
    }

    public function sort($columns = 'value')
    {
        return parent::sort($columns);
    }
}