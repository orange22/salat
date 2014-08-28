<?php
/**
 * Junction table update behavior
 * Update model HAS_MANY/MANY_MANY relations junction table
 *
 * Before calling updateRelated(), model have to be reattached:  $model-><behavior name>->attach($model);
 *
 * Usage:
 *  $model->updateRelated('relationName', 'newData')
 */
class JunctionBehavior extends CActiveRecordBehavior
{
    /**
     * Relations to update
     * Array of arrays {relationName: {table, secondaryColumn, idColumn, [primaryColumn]}, ...}
     * Update <primaryColumn> in <table> with new data using <secondaryColumn> = <idColumn> as base/where condition
     * secondaryColumn and primaryColumn are columns from <table>
     * idColumn is column from model
     *
     * @var array
     */
    public $relations = array();

    /**
     * Update related junction table
     *
     * @param string $rel Relation name
     * @param array $newData Array of new data keys (no validation performing; filtering out empty elements)
     * @return int Number of inserted+deleted rows
     * @throws CException
     */
    public function updateRelated($rel, $newData)
    {
        if(!isset($this->relations[$rel]))
            throw new CException(Yii::t('yiiext', 'Relation "{rel}" not configured', array('{rel}' => $rel)));

        $params = $this->relations[$rel];

        $params['primaryColumn'] = !isset($params['primaryColumn'])
            ? $this->tablePrimaryColumn($params['idColumn'])
            : $params['primaryColumn'];

        $curData = $this->currentData(
            $this->owner->{$params['idColumn']},
            $params['table'],
            $params['secondaryColumn'],
            $params['primaryColumn']
        );

        $sqlData = $this->prepareJunctionData($curData, array_filter($newData));

        return $this->updateJunction(
            $sqlData,
            $params['table'],
            $params['idColumn'],
            $params['secondaryColumn'],
            $params['primaryColumn']
        );
    }

    /**
     * Prepare insert/delete array for junction table update
     *
     * @param array $old Array of old(current) ID's
     * @param array $new Array of new ID's
     * @return array Array with appropriate insert/delete arrays
     */
    protected function prepareJunctionData($old, $new)
    {
        // ID's to delete
        $delete = array_diff($old, $new);

        // ID's to insert
        $insert = array_diff($new, $old);

        return array('insert' => $insert, 'delete' => $delete);
    }

    /**
     * Current related data
     *
     * @param int $id Owner model ID
     * @param string $table Junction table name
     * @param string $secondaryColumn Junction table updatable column name
     * @param string $primaryColumn Junction table primary column name
     * @return array
     */
    protected function currentData($id, $table, $secondaryColumn, $primaryColumn)
    {
        return app()->db->createCommand()
            ->select($primaryColumn)
            ->from($table)
            ->where($secondaryColumn.' = :pc_id')
            ->queryColumn(array(':pc_id' => $id));
    }

    /**
     * Update junction table
     *
     * @param array $data Array with insert,delete keys which are arrays of ID's
     * @param string $table Table name to update
     * @param string $idColumn Model ID field name
     * @param string $primaryColumn Primary column
     * @param string $secondaryColumn Column name to update
     * @throws CDbException
     * @return int Number of inserted+deleted rows
     */
    protected function updateJunction($data, $table, $idColumn, $primaryColumn, $secondaryColumn)
    {
        $id = $this->owner->$idColumn;

        if(!$id && $this->owner->isNewRecord)
            return 0;

        if(!$id)
        {
            throw new CDbException(Yii::t('yiiext', 'Junction table "{table}" column "{idColumn}" ID value not defined', array(
                '{table}' => $table,
                '{idColumn}' => $idColumn,
            )));
        }

        $o = 0;
        $sql = app()->db->createCommand();
        if(!app()->db->getCurrentTransaction())
            $ta = app()->db->beginTransaction();

        try
        {
            if(!empty($data['delete']))
            {
                $o += $sql->delete($table,
                    array('and', $primaryColumn.' = :pk_id', array('IN', $secondaryColumn, $data['delete'])),
                    array(':pk_id' => $id)
                );
            }

            $sql->reset();

            foreach($data['insert'] as $item)
            {
                $o += $sql->insert($table,
                    array(
                        $primaryColumn => $id,
                        $secondaryColumn => $item
                    ));
            }

            if(isset($ta))
                $ta->commit();
        }
        catch(CDbException $e)
        {
            if(isset($ta))
                $ta->commit();
            throw $e;
        }

        return $o;
    }

    /**
     * Get table junction table primary secondaryColumn
     *
     * @param string $idColumn Table ID secondaryColumn name
     * @param string $primaryColumn Junction table primary secondaryColumn name
     * @return string
     */
    protected function tablePrimaryColumn($idColumn, $primaryColumn = null)
    {
        if($primaryColumn)
            return $primaryColumn;

        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_\1', get_class($this->owner)).'_'.$idColumn);
    }
}