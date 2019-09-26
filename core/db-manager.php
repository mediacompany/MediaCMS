<?php
$core->map('add_table',function($table_name,$sql) use ($core){
    // Global cope to access DB with PDO
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
$core->map('insert_on',function($table, $data) use ($core){
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
//$core->insert_on('example', array('bar_column' => 'bar2'));
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
//$core->update_on('example', array('bar_column' => 'barNaaaa','foo_column' => '2021-05-08 17:36:46'), array('ID' => 1));
$core->map('get_all',function($table,$data = "",$select = "*") use ($core){
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
//$core->get_all('examplea');
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
//$core->get_row('example','ID,foo_column')
$core->map('delete_on',function($table,$where) use ($core){
    $db = $core->db();
    $prefix = DBPREFIX;
    $key = array_keys($where)[0];
    $sql =  "DELETE FROM `{$prefix}_{$table}`  WHERE $key = {$where[$key]};";
    try {
        $stmt = $db->prepare($sql); 
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        die('<strong>Error:</strong> '.$e->getMessage());
    }
});
//$core->delete_on('example',array('ID' => 3));