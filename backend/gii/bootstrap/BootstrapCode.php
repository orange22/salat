<?php
/**
 * BootstrapCode class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('gii.generators.crud.CrudCode');

class BootstrapCode extendS CrudCode
{
    protected $attachAttributes = array(
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

    public function generateActiveRow($modelClass, $column)
    {
        /** @var $column CMysqlColumnSchema */
        if ($column->type === 'boolean' || $column->dbType === 'tinyint(1)')
            return "\$form->checkBoxRow(\$model, '{$column->name}')";
        else if (stripos($column->dbType,'text') !== false)
            return "\$form->textAreaRow(\$model, '{$column->name}', array('rows' => 5, 'cols' => 50, 'class' => 'span9'))";
        elseif(in_array($column->dbType, array('year', 'time', 'timestamp', 'date', 'datetime')) || strpos($column->name, 'date'))
        {
            switch($column->dbType)
            {
                case 'year':
                    $format = '%Y';
                break;
                case 'time':
                    $format = '%H:%M:%S';
                break;
                case 'date':
                    $format = '%Y-%m-%d';
                break;
                case 'timestamp':
                case 'datetime':
                default:
                    $format = '%Y-%m-%d %H:%M:%S';
                break;
            }
            $o = "\$form->textFieldRow(\$model, '{$column->name}', array('class' => 'span2')); ?>\n";
            $o .= <<<HTML
    <?php \$this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId(\$model, '{$column->name}'),
        'ifFormat' => '{$format}',
        'showsTime' => true,
        'language' => 'ru-UTF',
    ))
HTML;
            return $o;
        }
        else
        {
            if(in_array($column->name, $this->attachAttributes))
                return "\$form->fileUploadRow(\$model, '{$column->name}', '".substr($column->name, 0, strpos($column->name, '_id'))."')";

            if(strpos($column->name, '_id') || strpos($column->name, '_pid'))
                return "\$form->dropDownListRow(\$model, '{$column->name}', array())";

            if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='passwordFieldRow';
            else
                $inputField='textFieldRow';

            if($column->name === 'sort')
                return "\$form->{$inputField}(\$model, '{$column->name}', array('class' => 'span2'))";

            if ($column->type!=='string' || $column->size===null)
                return "\$form->{$inputField}(\$model, '{$column->name}', array('class' => 'span9'))";
            else
                return "\$form->{$inputField}(\$model, '{$column->name}', array('class' => 'span9', 'maxlength' => $column->size))";
        }
    }

    public function generateLangActiveRow($modelClass, $column)
    {
        /** @var $column CMysqlColumnSchema */
        $colName = "[{\$language}]{$column->name}";

        if ($column->type === 'boolean' || $column->dbType === 'tinyint(1)')
            return "\$form->checkBoxRow(\$model, \"{$colName}\")";
        else if (stripos($column->dbType,'text') !== false)
            return "\$form->textAreaRow(\$model, \"{$colName}\", array('rows' => 5, 'cols' => 50, 'class' => 'span9'))";
        elseif(in_array($column->dbType, array('year', 'time', 'timestamp', 'date', 'datetime')) || strpos($column->name, 'date')
        )
        {
            switch($column->dbType)
            {
                case 'year':
                    $format = '%Y';
                    break;
                case 'time':
                    $format = '%H:%M:%S';
                    break;
                case 'date':
                    $format = '%Y-%m-%d';
                    break;
                case 'timestamp':
                case 'datetime':
                default:
                    $format = '%Y-%m-%d %H:%M:%S';
                    break;
            }
            $o = "\$form->textFieldRow(\$model, '{$column->name}', array('class' => 'span2')); ?>\n";
            $o .= <<<HTML
    <?php \$this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId(\$model, "{$colName}"),
        'ifFormat' => '{$format}',
        'showsTime' => true,
        'language' => 'ru-UTF',
    ))
HTML;
            return $o;
        }
        else
        {
            if(in_array($column->name, $this->attachAttributes))
                return "\$form->fileUploadRow(\$model, \"{$colName}\", '".substr($colName, -3)."')";

            if(strpos($column->name, '_id') || strpos($column->name, '_pid'))
                return "\$form->dropDownListRow(\$model, \"{$colName}\", array())";

            if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='passwordFieldRow';
            else
                $inputField='textFieldRow';

            if($column->name === 'sort')
                return "\$form->{$inputField}(\$model, \"{$colName}\", array('class' => 'span2'))";

            if ($column->type!=='string' || $column->size===null)
                return "\$form->{$inputField}(\$model, \"{$colName}\", array('class' => 'span9'))";
            else
                return "\$form->{$inputField}(\$model, \"{$colName}\", array('class' => 'span9', 'maxlength' => $column->size))";
        }
    }
}
