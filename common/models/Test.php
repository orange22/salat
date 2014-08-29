<?php
/**
 * This is the model class for table "{{test}}".
 *
 * The followings are the available columns in table '{{test}}':
 * @property integer $id
 * @property string $text
 *
 * @method Test active
 * @method Test cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Test indexed($column = 'id')
 * @method Test language($lang = null)
 * @method Test select($columns = '*')
 * @method Test limit($limit, $offset = 0)
 * @method Test sort($columns = '')
 */
class Test extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Test the static model class
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
        return '{{test}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('text', 'safe'),
        
            array('id, text', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'text' => Yii::t('backend', 'Text'),
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
		$criteria->compare('t.text',$this->text,true);

        return parent::searchInit($criteria);
    }
}