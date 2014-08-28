<?php
/**
 * This is the model class for table "{{seo}}".
 *
 * The followings are the available columns in table '{{seo}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property string $entity
 * @property string $title
 * @property string $keywords
 * @property string $description
 *
 * @method Seo active
 * @method Seo cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Seo indexed($column = 'language_id')
 * @method Seo language($lang = null)
 * @method Seo select($columns = '*')
 * @method Seo limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property Language $language
 */
class Seo extends BaseActiveRecord
{
    /**
     * Find SEO model or make new one
     *
     * @param LangActiveRecord $model
     * @return Seo
     */
    public function findOrNew($model)
    {
        
		
        $attr = array(
            'pid' => $model->id,
            'entity' => $model->classId(true),
        );
		$seoModel = self::model()->findByAttributes($attr);

        if(!$seoModel)
        {
        	$seoModel = new Seo();
			$seoModel->setAttributes($attr);
		}
		
        return $seoModel;
    }

    /**
     * Fetch model meta
     *
     * @param array|LangActiveRecord $model Model
     * @return null|Seo
     */
    public function fetchMeta($model)
    {
       /*
        if(!isset($model['pid']))
                   return null;*/
       

        $params = array(
            'pid' => $model['pid'],
            'entity' => is_object($model) && $model instanceof BaseActiveRecord ? $model->classId(true) : $model['entity'],
        );

        $o = self::model()->cache()->findByAttributes($params);

        return ($o ? $o->getAttributes(array('title', 'keywords', 'description')) : null);
    }

    /**
     * Delete model meta
     *
     * @param array|LangActiveRecord $model
     * @throws CHttpException
     * @return int Model deleteAllByAttributes() result
     */
    public function deleteMeta($model)
    {
        $params = array(
            'pid' => $model['pid'],
            'entity' => is_object($model) && $model instanceof BaseActiveRecord ? $model->classId(true) : $model['entity']
        );

        if(!isset($params['pid'], $params['entity']))
            throw new CHttpException(500, Yii::t('backend', 'Not enough params to perform action.'));

        return self::model()->deleteAllByAttributes($params);
    }

    /**
     * Save meta for model
     *
     * @param array $data Data to save [title, keywords, description]
     * @param LangActiveRecord $model
     * @throws CHttpException
     * @return bool Models save() result
     */
    public function saveMeta($data, $model)
    {
    	$seoModel = self::model()->findOrNew($model);
		$seoModel->attributes = $data;
		if(strlen($data['title'])<1 && strlen($data['keywords'])<1 && strlen($data['description'])<1 && $seoModel->id>0)
		return $seoModel->delete();
		elseif(strlen($data['title'])>0 || strlen($data['keywords'])>0 || strlen($data['description'])>0)
		return $seoModel->save();
    }

    /*
    public function fixedAttributes()
        {
            return CMap::mergeArray(parent::fixedAttributes(), array(
                'pid',
                'entity',
            ));
        }*/
    

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Seo the static model class
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
        return '{{seo}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('entity, pid', 'required'),
            array('entity', 'length', 'max' => 8),
            array('title', 'length', 'max' => 256),
            array('keywords, description', 'safe'),

            array('id, pid,  entity, title, keywords, description', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
            'dish' => array(self::BELONGS_TO, 'Dish', 'id' , 'condition' => 'entity = "dish"'),
            //'post' => array(self::BELONGS_TO, 'Post', array('pid', 'language_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => 'PID',
            'entity' => Yii::t('backend', 'Entity'),
            'title' => Yii::t('backend', 'Title'),
            'keywords' => Yii::t('backend', 'Keywords'),
            'description' => Yii::t('backend', 'Description'),
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
		$criteria->compare('t.pid',$this->pid);
		$criteria->compare('t.entity',$this->entity,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.keywords',$this->keywords,true);
		$criteria->compare('t.description',$this->description,true);

        return parent::searchInit($criteria);
    }
}