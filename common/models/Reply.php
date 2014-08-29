<?php
/**
 * This is the model class for table "{{reply}}".
 *
 * The followings are the available columns in table '{{reply}}':
 * @property integer $id
 * @property string $login
 * @property integer $image_id
 * @property string $detail_text
 * @property integer $status
 * @property integer $sort
 * @property string $date_create
 *
 * @method Reply active
 * @method Reply cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Reply indexed($column = 'id')
 * @method Reply language($lang = null)
 * @method Reply select($columns = '*')
 * @method Reply limit($limit, $offset = 0)
 * @method Reply sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class Reply extends BaseActiveRecord
{
    public $title;
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
     * @return Reply the static model class
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
        return '{{reply}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('login', 'required'),
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('login', 'length', 'max' => 255),
            array('image_id, detail_text, date_create', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, login, image_id, detail_text, status, sort, date_create', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => Yii::t('backend', 'Login'),
            'image_id' => Yii::t('backend', 'Image'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'date_create' => Yii::t('backend', 'Date Create'),
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
		$criteria->compare('t.login',$this->login,true);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.date_create',$this->date_create,true);

        return parent::searchInit($criteria);
    }
}