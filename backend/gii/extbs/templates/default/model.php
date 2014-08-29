<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>
/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
 *
<?php foreach(array(
    'active',
    'cache($duration = null, $dependency = null, $queryCount = 1)',
    'indexed($column = \''.($this->baseClass === 'LangActiveRecord' ? 'language_id' : 'id').'\')',
    'language($lang = null)',
    'select($columns = \'*\')',
    'limit($limit, $offset = 0)',
    'sort($columns = \'\')') as $method): ?>
 * @method <?php echo $modelClass.' '.$method."\n"; ?>
<?php endforeach; ?>
<?php $relatedModels = array(); ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
  if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];
        $relatedModels[] = $relationModel;

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
  }
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
<?php if($this->baseClass === 'LangActiveRecord') { ?>
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
<?php foreach($columns as $column) { ?>
<?php if($column->name === 'language_id') continue; ?>
<?php if(in_array($column->name, array('entity', 'file_id', 'audio_id', 'video_id', 'avatar_id', 'logo_id', 'logo2_id', 'mini_id', 'small_id', 'thumb_id', 'image_id', 'middle_id', 'big_id', 'picture_id', 'media_id'))) { ?>
            '<?php echo $column->name; ?>',
<?php continue; ?>
<?php } ?>
<?php if(strpos($column->name, '_pid') !== false) { ?>
            '<?php echo $column->name; ?>',
<?php } ?>
<?php if(strpos($column->name, '_id') !== false) { ?>
            '<?php echo $column->name; ?>',
<?php } ?>
<?php if(strpos($column->name, 'date') !== false) { ?>
            '<?php echo $column->name; ?>',
<?php } ?>
<?php } ?>
        ));
    }
<?php } // LangActiveRecord ?>
<?php if(in_array('File', $relatedModels)) { ?>

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
<?php foreach(array_reverse($columns) as $column): ?>
<?php if(!in_array($column->name, array('logo_id', 'avatar_id', 'mini_id', 'small_id', 'thumb_id', 'image_id', 'middle_id', 'big_id', 'picture_id', 'media_id'))) continue; ?>
                    '<?php echo $column->name; ?>',
<?php endforeach; ?>
                ),
                'fileAttributes' => array(
<?php foreach(array_reverse($columns) as $column): ?>
<?php if(!in_array($column->name, array('file_id', 'audio_id', 'video_id',))) continue; ?>
                    '<?php echo $column->name; ?>',
<?php endforeach; ?>
                ),
            )
        );
    }
<?php } ?>

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return <?php echo $modelClass; ?> the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	<?php if($connectionId!='db'):?>

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
        return Yii::app()-><?php echo $connectionId ?>;
    }
	<?php endif?>

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '<?php echo $tableName; ?>';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
        <?php foreach($rules as $rule): ?>
    <?php echo $rule.",\n"; ?>
        <?php endforeach; ?>

            array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        <?php foreach($relations as $name => $relation): ?>
    <?php echo "'$name' => $relation,\n"; ?>
        <?php endforeach; ?>
);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
    <?php foreach($labels as $name => $label): ?>
<?php if(in_array($label, array('ID', 'Pid'))) { ?>
        <?php echo "'$name' => '".strtoupper($label)."',\n"; ?>
<?php } else { ?>
        <?php echo "'$name' => Yii::t('backend', '$label'),\n"; ?>
<?php } ?>
    <?php endforeach; ?>
    );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        <?php
        foreach($columns as $name => $column)
        {
            if($name == 'language_id')
                continue;

            if($column->type === 'string')
            {
                echo "\t\t\$criteria->compare('t.$name',\$this->$name,true);\n";
            }
            else
            {
                echo "\t\t\$criteria->compare('t.$name',\$this->$name);\n";
            }
        }

        $with = array();
        foreach($relations as $relationVar => $relationData)
        {
            if(!preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relationData, $matches))
                continue;

            $relationType = $matches[1];
            $relationModel = $matches[2];

            if($relationType !== 'BELONGS_TO')
                continue;

            if(in_array($relationModel, array('Language', 'File')))
                continue;

            $with[] = $relationVar;
        }
        if(!empty($with))
            echo "\n\t\t\$criteria->with = array('".implode("', '", $with)."');\n";
        ?>

        return parent::searchInit($criteria);
    }
}