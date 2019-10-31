<?php
/**
 * Create table on DB.
 *
 * create table on DB configure on config.php
 *
 * @since 1.0.0
 *
 * @param string $table_name Name of the table without prefix, this added automatic
 * @param string $sql standar SQL for create a table
 * @param bool  $echo    Whether to echo or just return the string
 * @return none
 */
$core->map('add_table',function($table_name,$sql) use ($core){
    // Global scope to access DB with PDO
    $db = $core->db();
    $prefix = DBPREFIX; // prefix define on config.php
    $create_sql = " 
    CREATE TABLE IF NOT EXISTS `{$prefix}_{$table_name}` (
      {$sql}
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    COMMIT;
    ";
    $db->exec($create_sql);
});
/**
 * Insert a new row on DB.
 *
 * Easy function to insert a new row on DB based on assoc array
 *
 * @since 1.0.0
 *
 * @param string $table Name of table on DB to insert data
 * @param string $data array of data, key is column on DB
 * @return int of last insert
 */
$core->map('insert_on',function($table, $data = array()) use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $values = "";
    $limit = count($data);
    $i = 1;
    foreach ( $data as $value ) {
        if (is_string($value) && $i != $limit) {
            $values .= "'{$value}',";
        }else if (!is_string($value) && $i != $limit){
             $values .= $value.",";
        }else if (is_string($value) && $i == $limit){
            $values .= "'{$value}'";
        }else{
            $values .= $value;
        }
        $i++;
    }
    $fields  = '`' . implode( '`, `', array_keys( $data ) ) . '`';
    $sql = "INSERT INTO `{$prefix}_{$table}` ($fields) VALUES ($values)";
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }    
});
/**
 * Update content of row on DB.
 *
 * Update columns of row based on assoc array, the where condition is an simple array assoc
 *
 * @since 1.0.0
 *
 * @param string $table Name of table on DB to update data
 * @param array $data Array of data, key is column on DB
 * @param array $where Condition to make the update on specific row
 * @return true if update is successful
 */
$core->map('update_on',function($table, $data, $where) use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $fields = "";
    $limit = count($data);
    $i = 1;
    foreach ( $data as $key => $value ) {
        if (is_string($value) && $i != $limit) {
            $fields .= "{$key} = '{$value}', "; 
        }else if (!is_string($value) && $i != $limit){
            $fields .= "{$key} = {$value}, ";
        }else if (is_string($value) && $i == $limit){
            $fields .= "{$key} = '{$value}' ";
        }else{
            $fields .= "{$key} = {$value} ";
        }
        $i++;
    }
    $key = array_keys($where)[0];
    $sql = "UPDATE `{$prefix}_{$table}` SET $fields WHERE $key = {$where[$key]};";
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
/**
 * Get all rows of table.
 *
 * Easy function to get all rows on DB
 *
 * @since 1.0.0
 *
 * @param string $table Name of table on DB to select data
 * @param string $select Columns to get
 * @param string $data option to put where condition
 * @return object of all data
 */
$core->map('get_all',function($table,$select = "*",$data = "") use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $sql =  "SELECT $select FROM `{$prefix}_{$table}` ".$data;
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
/**
 * Get single row of table.
 *
 * Easy function to get single row on DB
 *
 * @since 1.0.0
 *
 * @param string $table Name of table on DB to select data
 * @param string $select Columns to get
 * @param string $data option to put where condition
 * @return object of all data
 */
$core->map('get_row',function($table,$select = "*",$data = "") use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $sql =  "SELECT $select FROM `{$prefix}_{$table}` ".$data." LIMIT 1";
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return $stmt->fetch();
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
/**
 * Delete a row of table.
 *
 * Easy function to delete single row on DB with a condition
 *
 * @since 1.0.0
 *
 * @param string $table Name of table on DB to delete data
 * @param array $where Condition to delete specific row
 * @return true if delete is successful
 */
$core->map('delete_on',function($table,$where) use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $key = array_keys($where)[0];
    $sql =  "DELETE FROM `{$prefix}_{$table}` WHERE $key = {$where[$key]};";
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
/**
 * Make a regular SQL query.
 *
 * This function make a regular SQL query
 *
 * @since 1.0.0
 *
 * @param string $query Standar SQL query this need check previus use
 * @return object of all data
 */
$core->map('query',function($query) use ($core){
    $db = $core->db();
    try {
        $stmt = $db->prepare($query); 
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
/**
 * Make a regular SQL execution.
 *
 * This function make a regular SQL execution
 *
 * @since 1.0.0
 *
 * @param string $query Standar SQL execution this need check previus use
 * @return true is successful
 */
$core->map('exec',function($query) use ($core){
    $db = $core->db();
    try {
        $db->exec($query);
        return true;
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});