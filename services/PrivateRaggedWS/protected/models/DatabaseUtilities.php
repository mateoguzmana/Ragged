<?php

class DatabaseUtilities extends CActiveRecord {

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    public function excecuteDatabaseStoredFunctions($function) {
        try {
            $query = "SELECT " . $function . " AS `result`;";
            return YII::app()->db->createCommand($query)->queryAll();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseFunctions', $e);
        }
    }

    public function excecuteDatabaseStoredFunctionsQuery($function) {
        try {
            $query = "SELECT " . $function . " AS `result`;";
            return YII::app()->db->createCommand($query)->query();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredFunctionsQuery', $e);
        }
    }

    public function excecuteDatabaseStoredFunctionsQueryScalar($function) {
        try {
            $query = "SELECT " . $function . ";";
            return YII::app()->db->createCommand($query)->queryScalar();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredFunctionsQueryScalar', $e);
        }
    }

    public function excecuteDatabaseStoredProcedures($function) {
        try {
            $query = "CALL " . $function;
            return YII::app()->db->createCommand($query)->queryAll();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredProcedures', $e);
        }
    }

    public function excecuteDatabaseStoredProceduresQuery($function) {
        try {
            $query = "CALL " . $function;
            return YII::app()->db->createCommand($query)->query();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredProceduresQuery', $e);
        }
    }

    public function excecuteDatabaseStoredProceduresQueryScalar($function) {
        try {
            $query = "CALL " . $function;
            return YII::app()->db->createCommand($query)->queryScalar();
        } catch (Exception $e) {
            $this->createLog('DatabaseUtilities', 'excecuteDatabaseStoredProceduresQueryScalar', $e);
        }
    }

    public function updatebasic($table, $set, $where) {
        try {
            $sql = "UPDATE ";
            $sql .= $table;
            $sql .= " SET " . $set;
            $sql .= " WHERE 1=1 " . $where;
            return $this::model()->setGeneralInsert($sql);
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'updatebasic', $ex);
            return "";
        }
    }

    public function createBasicSelectWithWhereAndGroup($columns, $table, $where, $group) {
        $query = "SELECT ";
        $query .= $columns . " FROM ";
        $query .= $table . " WHERE 1=1 ";
        $query .= $where . " GROUP BY ";
        $query .= $group;
        return (string) $query;
    }

    public function createBasicSelectWithWhere($columns, $table, $where) {
        $query = "SELECT ";
        $query .= $columns . " FROM ";
        $query .= $table . " WHERE 1=1 ";
        $query .= $where;
        return (string) $query;
    }

    public function excecuteQueryAll($query) {
        return YII::app()->db->createCommand($query)->queryAll();
    }

    public function excecuteQuery($query) {
        return YII::app()->db->createCommand($query)->query();
    }

    public function excecuteQueryScalar($query) {
        return YII::app()->db->createCommand($query)->queryScalar();
    }

    public function createBasicSelectWithWhereGroupAndOrder($columns, $table, $where, $group, $orderBy) {
        /* la función recibe las columnas a seleccionar, 
          la tabla de la que hace la selección y la condición where
          iniciando todas con (and/or) y separadas por espacios
          y los campos de la tabla por los que se desea agrupar */
        $query = $this->createBasicSelectWithWhereAndGroup($columns, $table, $where, $group);
        $query .= " ORDER BY " . $orderBy;
        return (string) $query;
    }

    public function createBasicSelectWithWhereUnion($columns, $arrQuerySelect, $where, $orderBy) {
        /* recibe un arreglo con la cantidad de consultas que deben estar en el union
         * de igual forma, la función recibe las columnas a seleccinar, 
         * la condición where iniciando todas con (and/or) y separadas por espacios
         * y los campos de la tabla por los que se desea agrupar
         */
        $query = "SELECT ";
        $query .= $columns . " FROM ( ";
        for ($i = 0; $i < count($arrQuerySelect); $i++) {
            $query .= $arrQuerySelect[$i];
            $query .= ($i != (count($arrQuerySelect) - 1)) ? " UNION " : "";
        }
        $query .= " )tbl WHERE 1=1 ";
        $query .= $where . " ORDER BY ";
        $query .= $orderBy;
        return (string) $query;
    }

    public function createSelectWithWhereGroupInnerJoin($columns, $tablesArray, $where, $groupBy) {
        $query = "SELECT ";
        $query .= $columns . " FROM ";
        $query .= $this->createInnerJoinMultiple($tablesArray);
        $query .= " WHERE 1=1 ";
        $query .= $where . " GROUP BY ";
        $query .= $groupBy;
        return (string) $query;
    }

    public function createSelectWithWhereGroupOrderAndInnerJoin($columns, $tablesArray, $where, $groupBy, $orderBy) {
        $query = "SELECT ";
        $query .= $columns . " FROM ";
        for ($i = 0; $i < count($tablesArray); $i++) {
            if ($i == 0) {
                $query .= $tablesArray[$i]['table'] . " " . $tablesArray[$i]['alias'];
            } else {
                $query .= " INNER JOIN " . $tablesArray[$i]['table'] . " " . $tablesArray[$i]['alias'];
                foreach ($tablesArray[$i]['references'] as $value) {
                    if ($value['condition'] == '') {
                        $query .= " ON " . $value['primary'] . " = " . $value['foreign'];
                    } else {
                        $query .= " " . $value['condition'] . " " . $value['primary'] . " = " . $value['foreign'];
                    }
                }
            }
        }
        $query .= " WHERE 1=1 ";
        $query .= $where . " GROUP BY ";
        $query .= $groupBy . " ORDER BY ";
        $query .= $orderBy;
        return (string) $query;
    }

    public function createBasicSelectWithWhereOrderAndUnion($columns, $arrQuerySelect, $where, $orderBy) {
        $query = "SELECT ";
        $query .= $columns . " FROM ( ";
        for ($i = 0; $i < count($arrQuerySelect); $i++) {
            $query .= $arrQuerySelect[$i];
            $query .= ($i != (count($arrQuerySelect) - 1)) ? " UNION " : "";
        }
        $query .= " )tbl WHERE 1=1 ";
        $query .= $where . " ORDER BY ";
        $query .= $orderBy;
        return (string) $query;
    }

    public function createInnerJoinMultiple($tablesArray) {
        $query = "";
        for ($i = 0; $i < count($tablesArray); $i++) {
            if ($i == 0) {
                $query .= $tablesArray[$i]['table'] . " " . $tablesArray[$i]['alias'];
            } else {
                $query .= " INNER JOIN " . $tablesArray[$i]['table'] . " " . $tablesArray[$i]['alias'];
                foreach ($tablesArray[$i]['references'] as $value) {
                    if ($value['condition'] == '') {
                        $query .= " ON " . $value['primary'] . " = " . $value['foreign'];
                    } else {
                        $query .= " " . $value['condition'] . " " . $value['primary'] . " = " . $value['foreign'];
                    }
                }
            }
        }
        return (string) $query;
    }

    public function parseArrayOfObjectsToQueryIn($column, $array) {
        $queryResponseHelper = '';
        $queryResponse = " AND $column IN (";
        foreach ($array as $value) {
            $queryResponseHelper .= ",'" . $value . "'";
        }
        $queryResponse .= substr($queryResponseHelper, 1) . ')';
        return (string) $queryResponse;
    }

    public function insertbasic($table, $columns, $values) {
        try {
            $sql = "INSERT INTO ";
            $sql .= $table;
            $sql .= " ($columns) VALUES ";
            $sql .= "($values)";
            return $this::model()->setGeneralInsert($sql);
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'insertbasic', $ex);
            return "";
        }
    }

    public function deletebasic($table, $where) {
        try {
            $sql = "DELETE FROM ";
            $sql .= $table;
            $sql .= " WHERE 1=1 ";
            $sql .= $where;
            return (string) $sql;
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'deletebasic', $ex);
            return "";
        }
    }

    public function insertblock($table, $columns, $values, $columnsUpdate) {
        try {
            $sql = "INSERT INTO ";
            $sql .= $table;
            $sql .= " ($columns) VALUES ";
            for ($counter = 0; $counter < count($values); $counter++) {
                $sql .= " (" . $values[$counter] . ")";
                if ($counter < count($values) - 1)
                    $sql .=",";
            }
            $sql .= " ON DUPLICATE KEY UPDATE ";
            for ($counter = 0; $counter < count($columnsUpdate); $counter++) {
                $sql .= $columnsUpdate[$counter] . " = VALUES(" . $columnsUpdate[$counter] . "),";
            }
            $sql = substr($sql, 0, -1);
            return $this::model()->setGeneralInsert($sql);
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'insertblock', $ex);
            return "";
        }
    }
    
    public function getMaxId($table) {
        try {
            $sql = "SELECT COUNT(*) AS NumRows FROM $table;";
            $response = YII::app()->db->createCommand($sql)->queryRow();
            foreach ($response as $row) {
                $id = $row['NumRows'];
            }
            return $id++;
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'getMaxId', $ex);
            return 0;
        }
    }

    public function updateAutoIncrement($table) {
        try {
            $id = $this->getMaxId("$table");
            $sql = "ALTER TABLE $table auto_increment=$id;";
            return YII::app()->db->createCommand($sql)->query();
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'updateAutoIncrement', $ex);
            return 0;
        }
    }

    public function setGeneralInsert($query) {
        try {
            return YII::app()->db->createCommand($query)->query();
        } catch (Exception $ex) {
            $this->createLog('DatabaseUtilities', 'setGeneralInsert', $ex);
            return false;
        }
    }

}
