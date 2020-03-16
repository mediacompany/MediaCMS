<?php
$app->register_module(array('name' => 'Portfolios','url' => 'portfolios','admin-url' => 'portfolios', 'order' => 9,'submenu' => array('portfolio/0' => 'Nuevo Proyecto','portfolios' => 'Editar Proyectos')));
$db = $app->db();
$prefix = DBPREFIX;
$post_sql = " 
CREATE TABLE IF NOT EXISTS `{$prefix}_portfolio` (
  `ID` BIGINT NOT NULL AUTO_INCREMENT,
  `post_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_title` VARCHAR(255) NOT NULL,
  `post_text` TEXT NOT NULL,
  `post_category` TEXT NULL,
  `post_slug` VARCHAR(255) NOT NULL,
  `post_order` INT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;
";
$db->exec($post_sql);
$post_cat_sql = " 
CREATE TABLE IF NOT EXISTS `{$prefix}_portfolio_categories` (
  `ID` BIGINT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(255) NOT NULL,
  `category_slug` VARCHAR(255) NOT NULL,
  `category_order` INT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;
";
$db->exec($post_cat_sql);
$app->map('category_names',function($cats){
    $cats = explode(',', $cats);
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if ($cats[0] != "") {
      foreach ($cats as $key => $cat) {
              $category = $db->prepare("SELECT category_name FROM `{$prefix}_portfolio_categories` WHERE ID = {$cat} ");
              $category->execute();
              $category = $category->fetchAll();
              if (end($cats) != $cat) {
                  echo $category[0]->category_name.', ';
              }else{
                  echo $category[0]->category_name;
              }
      }
    }else{
      echo "-";
    }
});

$app->map('get_portfolios',function($cat = null, $month = null, $year = null, $order = 'ASC'){
    //$cats = explode(',', $cats);
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $query_get = $app->request()->query;
    $cat = (!empty($query_get['bycategory']) && $query_get['bycategory'] != 'all')? $query_get['bycategory'] : 'all';
    $month = (!empty($query_get['bymonth']) && $query_get['bymonth'] != 'all')? $query_get['bymonth'] : null;
    $year = (!empty($query_get['byyear']) && $query_get['byyear'] != 'all')? $query_get['byyear'] : null;
    $order = (!empty($query_get['order']))? $query_get['order'] : 'ASC';
    $sql = "SELECT * FROM {$prefix}_portfolio ";
    if($cat && $cat == 'all'){
       $sql .= "WHERE ID > 0 ";
    }
    if ($cat && $cat != 'all') {
        $sql .= "WHERE post_category LIKE '%$cat%' ";
    }
    if ($month) {
        $sql .= "AND MONTH(post_date) = $month ";
    }
    if ($year) {
        $sql .= "AND YEAR(post_date) = $year ";
    }
    $sql .= "ORDER BY ID $order";
    //echo $sql;die;
    $posts = $db->prepare( $sql ); 
    $posts->execute();
    return $posts->fetchAll();
});

$app->map('get_portfolio',function($ID = null){
    //$cats = explode(',', $cats);
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if (!$ID) {
        return 'no ID SET';die();
    }
    $sql = "SELECT * FROM {$prefix}_portfolio WHERE ID = {$ID}";
    $posts = $db->prepare( $sql ); 
    $posts->execute();
    /*if ($cats[0] != "") {
      foreach ($cats as $key => $cat) {
              $category = $db->prepare("SELECT category_name FROM `{$prefix}_posts_category` WHERE ID = {$cat} ");
              $category->execute();
              $category = $category->fetchAll();
              if (end($cats) != $cat) {
                  echo $category[0]->category_name.', ';
              }else{
                  echo $category[0]->category_name;
              }
      }
    }else{
      echo "-";
    }*/
    return $posts->fetchAll()[0];
});

$app->route('/portfolios', function(){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $app->render('admin/global', 
        array('title' => 'Portfolio - MediaCore',
          'classb' => 'novedades_admin',
          'section' => 'novedades_admin',
          'section_title' => 'Portfolios',
          'module_file' => __DIR__.'/admin.php'

        )
    );
});
$app->route('/portfolios/category', function(){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $app->render('admin/global',
        array('title' => 'Portfolio/Categorias - MediaCore',
          'classb' => 'faq_admin',
          'section' => 'faq_admin',
          'section_title' => 'Portfolio/Categorias',
          'module_file' => __DIR__.'/portfolios-cat.php' // load  specific module
        )
    );
});
$app->route('GET /portfolio/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(8,10));
    if ($id == 0) {
        $section_title = 'Nueva noticia';
    }else{
        $section_title = 'Editar noticia: '.$id;
    }
    $app->render('admin/global', 
        array('title' => 'Portfolios - MediaCore',
          'classb' => 'novedades_admin',
          'section' => 'novedades_admin',
          'section_title' => $section_title,
          'id' => $id,
          'data' => $app->get_portfolio($id),
          'module_file' => __DIR__.'/portfolio.php' // load  specific module
        )
    );
});

$app->route('POST /portfolio/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $db = $app->db();
    $request = $app->request()->data;
    $prefix = DBPREFIX;
    $post_category = '';
    if ($request->portfolio_category) {
      $portfolio_category = implode(',', $request->portfolio_category);
    }
    if ($request->portfolio_service) {
      $portfolio_service = implode(',', $request->portfolio_service);
    }
    $extras = array(
      "project_url"=>   $request->portfolio_url,
      "client"=>   $request->portfolio_client,
      "project_date"=>  $request->portfolio_date,
      "servicios" => $portfolio_service,
      "cover" => $request->portfolio_image,
      "cover-small" => $request->portfolio_image_small
        );
    $extra_data = serialize($extras);
    if ($id == '0') {
        $sql_post = "
        INSERT INTO `{$prefix}_portfolio`(`title`, `category`, `description_es`, `description_en`, `description_pt`, `slug`, 
        `extra_data`,`portfolio_order`, `portfolio_visibility`) 
        VALUES ('$request->portfolio_title','$portfolio_category','$request->portfolio_description_es','$request->portfolio_description_en','$request->portfolio_description_pt','$request->portfolio_slug',
        '$extra_data',$request->portfolio_order,$request->portfolio_visibility)
        ";

    }else{ // else update info of user ID = $id
      $sql_post = "UPDATE `{$prefix}_portfolio` SET 
        `title` = '$request->portfolio_title',
        `category` = '$portfolio_category',
        `description_es` = '$request->portfolio_description_es',
        `description_en` = '$request->portfolio_description_en',
        `description_pt` = '$request->portfolio_description_pt',
        `slug` = '$request->portfolio_slug',
        `extra_data` = '$extra_data',
        `portfolio_order` = '$request->portfolio_order',
        `portfolio_visibility` = '$request->portfolio_visibility' 
        WHERE `ID` = {$id}";
    }
    $stmt = $db->prepare($sql_post);
    $stmt->execute();
    if ($id == 0) {
      $id = $db->lastInsertId();
      $app->redirect('/portfolio/'.$id);
      die();
    }
    $app->redirect('/portfolio/'.$id);
    die();
});
