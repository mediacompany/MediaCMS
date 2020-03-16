<?php
$app->route('/', function(){
    global $app;
    if(empty($_SESSION['mcb_user'])){
         $app->render('admin/login', 
            array('title' => 'Ingresar'.SITENAME,
                'classb' => 'login',
                'section' => 'login',
                'section_title' => 'Ingresá tus datos de acceso'
            )
         );
         die();
    }
    $app->render('admin/home', 
        array('title' => 'Estadisticas'.SITENAME,
            'classb' => 'admin-home',
            'section' => 'admin-home',
            'section_title' => 'Estadisticas'
        )
    );
});
$app->route('/users', function(){
    global $app;
    $app->checkAuthPermission(array(10));
    $app->render('admin/users', 
        array('title' => 'Usuario'.SITENAME,
          'classb' => 'users',
          'section' => 'users',
          'section_title' => 'Usuarios'
        )
    );
});
// list respurce by id
$app->route('GET /user/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(10));
    $db = $app->db();
    $prefix = DBPREFIX;
    if ($id == 0) {
        $section_title = 'Nuevo Usuario';
        $data[0] = (object) array('user_user'=>'','user_name'=>'','user_email'=>'','user_phone'=>'',
            'user_birthday'=>'','user_level'=>'','user_info'=>'','user_ustate'=>'');
    }else{
        $data = $db->prepare("SELECT * FROM `{$prefix}_users` WHERE ID = {$id}");
        $data->execute();
        $data = $data->fetchAll();
        $section_title = 'Editar Usuario: '.$data[0]->user_name;
    }
    $app->render('admin/user', 
        array('title' => $section_title.SITENAME,
            'classb' => 'resource',
            'section' => 'resource',
            'section_title' => $section_title,
            'id' => $id,
            'data' => $data[0]
        )
    );
});
// Form send with client ID 0 = new
$app->route('POST /user/@id', function($id){
    global $app;
    $app->checkAuthPermission(array(10));
    $db = $app->db();
    $request = $app->request()->data;
    $prefix = DBPREFIX;
    // if 0 create new client
    $hashpass = password_hash($request->user_password, PASSWORD_DEFAULT);
    if ($id == '0') {
            $sql_users = "INSERT INTO `{$prefix}_users`
            (`user_user`, `user_password`, `user_name`, `user_email`, `user_phone`,
            `user_birthday`, `user_level`,`user_info`, `user_ustate`) 
            VALUES ('$request->user_user','$hashpass','$request->user_name','$request->user_email','$request->user_phone',
            '$request->user_birthday','$request->user_level','$request->user_info','$request->user_ustate')";
            //$id = $db->lastInsertId();
    }else{ // else update info of user ID = $id
        $sql_users = "UPDATE `{$prefix}_users` SET 
            `user_user` = '$request->user_user',";
            if (!empty($request->user_password)) {
                $sql_users .= "`user_password` = '$hashpass',";
            }
            $sql_users .= "
            `user_name` = '$request->user_name',
            `user_email` = '$request->user_email',
            `user_phone` = '$request->user_phone',
            `user_birthday` = '$request->user_birthday',
            `user_level` = '$request->user_level',
            `user_info` = '$request->user_info',
            `user_ustate` = '$request->user_ustate' 
            WHERE `ID` = {$id}";
    }
    $stmt = $db->prepare($sql_users);
    $stmt->execute();
    //$app->notify_helper_add(array('type' => 'success', 'expire' => time()+10, 'created' => time(), 'text' => '<strong>Usuario creado exitosamente!</strong>.'));
    $app->redirect('/users');
    die();
});

$app->route('/out', function(){
  global $app;
  unset($_SESSION['mcb_user']);
  session_destroy();
  $app->redirect('/');
});

$app->route('POST /ajax', function(){
    global $app;
    $db = $app->db();
    $prefix = DBPREFIX;
    $data = '';
    $request = $app->request()->data;
    $action = $request->action;
    switch ( $action ) {
        case 'sign-in':
                $stmt = $db->prepare("SELECT * FROM {$prefix}_users WHERE `user_user` = '{$request->login_user}' LIMIT 1"); 
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_OBJ);
                //var_dump($user);die;
                if(empty($user)){
                    $data = array( 'state' => 0,'action' => 'show-error','param' => 'Usuario Incorrecto 🤨' );
                    $app->json($data);
                    die();
                }else{
                    //var_dump($user);die;
                    if ($user->user_ustate === 0) {
                        $data = array( 'state' => 0,'action' => 'show-error','param' => 'Usuario Inactivo 😒' );
                        $app->json($data);
                        die();
                    }
                    $hash = $user->user_password;
                    if (password_verify($request->login_password, $hash)) {
                        $_SESSION['mcb_user'] = array(
                            'ID' => $user->ID,
                            'name' => $user->user_name,
                            'email' => $user->user_email,
                            'level' => $user->user_level
                        );
                        $data = array('state' => 1,'action' => 'reload','param' => null);
                    } else {
                        $data = array('state' => 0,'action' => 'show-error','param' => 'Contraseña Incorrecta 😓');
                    }
                }
                $app->json($data);
            break;
        case 'chatbot':
                $lead_chat = base64_encode($request->lead_chat);
                $stmt = $db->prepare("INSERT INTO `{$prefix}_leads`(`lead_name`, `lead_email`, `lead_phone`, `lead_question`, `lead_property`, `lead_meta`, `lead_chat`) 
                VALUES ('$request->lead_name','$request->lead_email','$request->lead_phone','$request->lead_question','$request->lead_property','$request->lead_meta','$lead_chat')"); 
                $stmt->execute();
                echo "ok";
            break;
        case 'delete':
                $condval =  explode(':', $request->sqlcondval);
                $lead_chat = base64_encode($request->lead_chat);
                $stmt = $db->prepare( "DELETE FROM `mh_portfolio` WHERE `$condval[1]` = $condval[0]"); 
                $stmt->execute();
                $data = array( 'state' => 0, 'action' => 'delete_item','param' => $request->domresp );
                $app->json($data);
                die();
            break;
        default:
                $app->json(array('state' => 0,'action' => null,'param' => null));
            break;
    }
});
