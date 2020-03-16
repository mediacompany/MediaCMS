<?php
$app->register_module(array('name' => 'Noticias','url' => 'posts','admin-url' => 'posts', 'order' => 3,'submenu' => array('post/0' => 'Nuevo Post','posts/category' => 'Categorias')));
$db = $app->db();
$prefix = DBPREFIX;
$post_sql = " 
CREATE TABLE IF NOT EXISTS `{$prefix}_posts` (
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
CREATE TABLE IF NOT EXISTS `{$prefix}_posts_category` (
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
    }
});

$app->map('get_posts',function($cat = null, $month = null, $year = null, $order = 'DESC'){
    //$cats = explode(',', $cats);
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $query_get = $app->request()->query;
    $cat = (!empty($query_get['bycategory']) && $query_get['bycategory'] != 'all')? $query_get['bycategory'] : 'all';
    $month = (!empty($query_get['bymonth']) && $query_get['bymonth'] != 'all')? $query_get['bymonth'] : null;
    $year = (!empty($query_get['byyear']) && $query_get['byyear'] != 'all')? $query_get['byyear'] : null;
    $order = (!empty($query_get['order']))? $query_get['order'] : 'DESC';
    $sql = "SELECT * FROM {$prefix}_posts ";
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

$app->map('get_post',function($ID = null){
    //$cats = explode(',', $cats);
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    if (!$ID) {
        return 'no ID SET';die();
    }
    $sql = "SELECT * FROM {$prefix}_posts WHERE ID = {$ID}";
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

$app->route('/posts', function(){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $app->render('admin/global', 
        array('title' => 'Noticias - MediaCore',
          'classb' => 'novedades_admin',
          'section' => 'novedades_admin',
          'section_title' => 'Noticias',
          'module_file' => __DIR__.'/admin.php'

        )
    );
});
$app->route('/posts/category', function(){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $app->render('admin/global',
        array('title' => 'Noticias/Categorias - MediaCore',
          'classb' => 'faq_admin',
          'section' => 'faq_admin',
          'section_title' => 'Noticias/Categorias',
          'module_file' => __DIR__.'/posts-cat.php' // load  specific module
        )
    );
});
$app->route('GET /post/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(8,10));
    if ($id == 0) {
        $section_title = 'Nueva noticia';
    }else{
        $section_title = 'Editar noticia: '.$id;
    }
    $app->render('admin/global', 
        array('title' => 'Noticias - MediaCore',
          'classb' => 'novedades_admin',
          'section' => 'novedades_admin',
          'section_title' => $section_title,
          'id' => $id,
          'data' => $app->get_post($id),
          'module_file' => __DIR__.'/post.php' // load  specific module
        )
    );
});

$app->route('POST /post/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(8,10));
    $db = $app->db();
    $request = $app->request()->data;
    $prefix = DBPREFIX;
    $post_category = '';
    if ($request->post_category) {
      $post_category = implode(',', $request->post_category);
    }
    if ($id == '0') {
        $sql_post = "
        INSERT INTO `{$prefix}_posts`(`post_title`, `post_text`, `post_category`, `post_image`, `post_slug`, `post_metatags`, 
        `post_metadescription`,`post_order`, `post_status`) 
        VALUES ('$request->post_title','$request->post_text','$post_category','$request->post_image','$request->post_slug','$request->post_metatags',
        '$request->post_metadescription',$request->post_order,$request->post_status)
        ";

    }else{ // else update info of user ID = $id
      $sql_post = "UPDATE `{$prefix}_posts` SET 
        `post_title` = '$request->post_title',
        `post_text` = '$request->post_text',
        `post_category` = '$post_category',
        `post_image` = '$request->post_image',
        `post_slug` = '$request->post_slug',
        `post_metatags` = '$request->post_metatags',
        `post_metadescription` = '$request->post_metadescription',
        `post_order` = '$request->post_order',
        `post_status` = '$request->post_status' 
        WHERE `ID` = {$id}";
    }
    $stmt = $db->prepare($sql_post);
    $stmt->execute();
    if ($id == 0) {
      $id = $db->lastInsertId();
      $app->redirect('/post/'.$id);
      die();
    }
    $app->redirect('/post/'.$id);
    die();
});
