<?php
Yii::import('system.gii.generators.model.ModelCode');
class ExtbsCode extends ModelCode
{
    public function prepare()
    {
        if(($pos = strrpos($this->tableName, '.')) !== false)
        {
            $schema = substr($this->tableName, 0, $pos);
            $tableName = substr($this->tableName, $pos + 1);
        }
        else
        {
            $schema = '';
            $tableName = $this->tableName;
        }
        if($tableName[strlen($tableName) - 1] === '*')
        {
			$tables=Yii::app()->{$this->connectionId}->schema->getTables($schema);
            if($this->tablePrefix != '')
            {
                foreach($tables as $i => $table)
                {
                    if(strpos($table->name, $this->tablePrefix) !== 0)
                    {
                        unset($tables[$i]);
                    }
                }
            }
        }
        else
        {
            $tables = array($this->getTableSchema($this->tableName));
        }

        $this->files = array();
        $templatePath = $this->templatePath;
        $this->relations = $this->generateRelations();

        foreach($tables as $table)
        {
            foreach($table->columns as &$column)
            {
                if($column->name != 'language_id'
                    && $column->type == 'string'
                    && ($column->name == 'id'
                        || $column->name == 'pid'
                        || strpos($column->name, '_id') !== false
                        || strpos($column->name, '_pid') !== false)
                )
                {
                    $column->type = 'integer';
                }
            }

            $tableName = $this->removePrefix($table->name);
            $className = $this->generateClassName($table->name);
            $params = array(
                'tableName' => $schema === '' ? $tableName : $schema.'.'.$tableName,
                'modelClass' => $className,
                'columns' => $table->columns,
                'labels' => $this->generateLabels($table),
                'rules' => $this->generateRules($table),
                'relations' => isset($this->relations[$className]) ? $this->relations[$className] : array(),
                'connectionId'=>$this->connectionId,
            );
            $this->files[] = new CCodeFile(
                Yii::getPathOfAlias($this->modelPath).'/'.$className.'.php',
                $this->render($templatePath.'/model.php', $params)
            );
        }
    }

    public function generateRules($table)
    {
        $rules = array();
        $required = array();
        $integers = array();
        $numerical = array();
        $length = array();
        $safe = array();
        $attach = array();
        $exist = array();

        $attachAttributes = array(
            'file_id',
            'audio_id',
            'video_id',
            'logo_id',
            'avatar_id',
            'mini_id',
            'small_id',
            'thumb_id',
            'image_id',
            'middle_id',
            'big_id',
            'picture_id'
        );

        // exclude attributes validated by base class
        $excludeAttributes = array('language_id', 'pid');
        foreach($table->columns as $column)
        {
            if(in_array($column->name, $excludeAttributes))
            {
                continue;
            }

            if($column->autoIncrement)
            {
                continue;
            }

            // attach column
            $isAttach = false;
            if(in_array($column->name, $attachAttributes))
                $isAttach = true;

            $r = !$column->allowNull && $column->defaultValue === null;
            if($r)
            {
                $required[] = $column->name;
            }
            if($column->type === 'integer' && !$isAttach)
            {
                $integers[] = $column->name;
            }
            else if($column->type === 'double')
            {
                $numerical[] = $column->name;
            }
            else if($column->type === 'string' && $column->size > 0)
            {
                $length[$column->size][] = $column->name;
            }
            else if(!$column->isPrimaryKey && !$r)
            {
                $safe[] = $column->name;
            }

            if($isAttach)
                $attach[] = $column->name;

            // foreign key
            if((strpos($column->name, '_pid') || strpos($column->name, '_id')) && !in_array($column->name, $attachAttributes))
                $exist[] = $column->name;
        }
        if($required !== array())
        {
            $rules[] = "array('".implode(', ', $required)."', 'required')";
        }
        if($integers !== array())
        {
            $rules[] = "array('".implode(', ', $integers)."', 'numerical', 'integerOnly' => true)";
        }
        if($numerical !== array())
        {
            $rules[] = "array('".implode(', ', $numerical)."', 'numerical')";
        }
        if($length !== array())
        {
            foreach($length as $len => $cols)
            {
                $rules[] = "array('".implode(', ', $cols)."', 'length', 'max' => $len)";
            }
        }
        if($safe !== array())
        {
            $rules[] = "array('".implode(', ', $safe)."', 'safe')";
        }

        if($attach !== array())
        {
            $rules[] = "array('".implode(', ', $attach)."', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload')";
        }

        if($exist !== array())
        {
            foreach($exist as $col)
            {
                $tail = 'id';
                if(strpos($col, '_pid'))
                    $tail = 'pid';

                list($className, $attrName) = preg_split('/_(?='.$tail.'$)/i', $col);
                $className = ucfirst(preg_replace_callback('/_([a-z])/i', create_function('$m', 'return strtoupper($m[1]);'), $className));
                $rules[] = "array('".$col."', 'exist', 'className' => '".$className."', 'attributeName' => '".$attrName."')";
            }
        }

        return $rules;
    }

    protected function generateRelationName($tableName, $fkName, $multiple)
    {
        if(strcasecmp(substr($fkName, -3), 'pid') === 0 && strcasecmp($fkName, 'pid'))
            $relationName = rtrim(substr($fkName, 0, -3), '_');
        elseif(strcasecmp(substr($fkName, -2), 'id') === 0 && strcasecmp($fkName, 'id'))
            $relationName = rtrim(substr($fkName, 0, -2), '_');
        else
            $relationName = $fkName;

        $relationName[0] = strtolower($relationName);

        if($multiple)
            $relationName = $this->pluralize($relationName);

        $names = preg_split('/_+/', $relationName, -1, PREG_SPLIT_NO_EMPTY);
        if(empty($names))
            return $relationName; // unlikely
        for($name = $names[0], $i = 1; $i < count($names); ++$i)
            $name .= ucfirst($names[$i]);

        $rawName = $name;
        $table = Yii::app()->{$this->connectionId}->schema->getTable($tableName);
        $i = 0;
        while(isset($table->columns[$name]))
            $name = $rawName.($i++);

        return $name;
    }


}