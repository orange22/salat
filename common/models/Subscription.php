<?php
/**
 * This is the model class for table "{{subscription}}".
 *
 * The followings are the available columns in table '{{subscription}}':
 * @property integer $id
 * @property integer $shoe_size_id
 * @property integer $cloth_size_id
 * @property string $email
 * @property string $gender
 *
 * @method Subscription active
 * @method Subscription cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Subscription indexed($column = 'id')
 * @method Subscription language($lang = null)
 * @method Subscription select($columns = '*')
 * @method Subscription limit($limit, $offset = 0)
 * @method Subscription sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Size $shoeSize
 * @property Size $clothSize
 */
class Subscription extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Subscription the static model class
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
        return '{{subscription}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('email', 'required'),
            array('shoe_size_id, cloth_size_id', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 128),
            array('gender', 'length', 'max' => 1),
            array('shoe_size_id', 'exist', 'className' => 'ShoeSize', 'attributeName' => 'id'),
            array('cloth_size_id', 'exist', 'className' => 'ClothSize', 'attributeName' => 'id'),

            array('id, shoe_size_id, cloth_size_id, email, gender', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'shoeSize' => array(self::BELONGS_TO, 'Size', 'shoe_size_id'),
            'clothSize' => array(self::BELONGS_TO, 'Size', 'cloth_size_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'shoe_size_id' => Yii::t('backend', 'Shoe Size'),
            'cloth_size_id' => Yii::t('backend', 'Cloth Size'),
            'email' => Yii::t('backend', 'Email'),
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
		$criteria->compare('t.shoe_size_id',$this->shoe_size_id);
		$criteria->compare('t.cloth_size_id',$this->cloth_size_id);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.gender',$this->gender,true);

		$criteria->with = array('shoeSize', 'clothSize');

        return parent::searchInit($criteria);
    }
}