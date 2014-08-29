<?php
/**
 * This is the model class for table "{{dishtype}}".
 *
 * The followings are the available columns in table '{{dishtype}}':
 * @property integer $id
 * @property string $title
 * @property integer $image_id
 * @property integer $status
 * @property integer $sort
 *
 * @method Dishtype active
 * @method Dishtype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Dishtype indexed($column = 'id')
 * @method Dishtype language($lang = null)
 * @method Dishtype select($columns = '*')
 * @method Dishtype limit($limit, $offset = 0)
 * @method Dishtype sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class Dishtype extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
        	'e' => array('class' => 'common.models.Entity'),
        	'seo' => array('class' => 'common.components.SeoBehavior'),
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id','image2_id'
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Dishtype the static model class
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
        return '{{dishtype}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('dpid,image_id,image2_id,detail_text', 'safe'),
            array('image_id,image2_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, detail_text, image_id, image2_id, status, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'dishtypeimage' => array(self::BELONGS_TO, 'File', 'image_id'),
            'dishtypeimage2' => array(self::BELONGS_TO, 'File', 'image2_id'),
            'coursetypeimage' => array(self::BELONGS_TO, 'File', 'image_id'),
            'dishCount' => array(self::STAT, 'Dish','dishtype_id'),
            'dishes' => array(self::HAS_MANY, 'Dish','dishtype_id'),
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
            'image_id' => Yii::t('backend', 'Image'),
            'image2_id' => Yii::t('backend', 'Image'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
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
        $criteria->addCondition("t.dpid IS NULL");
        $criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.image2_id',$this->image2_id);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('t.detail_text',$this->detail_text);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
    public function getTopDishes(){
        $connection=Yii::app()->db;
        $sql='SELECT
          gs_dishtype.id,
          gs_dishtype.dpid,
          gs_dishtype.`title`,
          gs_dishtype.`sort`,
          COUNT(gs_dish.id) AS cnt,
          CONCAT("/",gs_file.path,"/",gs_file.`file`) AS image
        FROM
          gs_dishtype
          LEFT JOIN `gs_dish`
          ON gs_dish.`dishtype_id`=gs_dishtype.id AND gs_dish.status=1
          LEFT JOIN gs_file
          ON gs_file.id=gs_dishtype.`image_id`
        WHERE  gs_dishtype.status=1
        AND gs_dishtype.dpid IS NULL
        GROUP BY gs_dishtype.id
        ORDER BY gs_dishtype.sort
        LIMIT 0,8';
        $command=$connection->createCommand($sql);
        $rows=$command->queryAll();
        $data=array();
        $data['types']=$rows;

        if($rows){
            $sql2='
            SELECT
              gs_dishtype.id,
              gs_dishtype.`title`,
              COUNT(gs_dish.id) AS cnt
            FROM
              gs_dishtype
              LEFT JOIN `gs_dish`
              ON gs_dish.`dishtype_id`=gs_dishtype.id AND gs_dish.status=1
            WHERE  gs_dishtype.status=1 AND gs_dishtype.dpid=18
            GROUP BY gs_dishtype.id
            ORDER BY gs_dishtype.sort';
            $command2=$connection->createCommand($sql2);
            $rows2=$command2->queryAll();
            if($rows2)
                $data['other']=$rows2;
        }
        if($data)
            return $data;
    }
}