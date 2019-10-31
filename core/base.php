<?php
require ABSPATH.'core/helper.php';
$core->route('/admin', function() use ($core){
    $core->set('flight.views.path', ABSPATH.'core/views');
    if(empty($_SESSION['mcb_user'])){
         $core->render('login', 
            array('title' => 'Ingresar'.SITENAME,
                'classb' => 'login',
                'section' => 'login',
                'section_title' => 'Ingresá tus datos de acceso'
            )
         );
         die();
    }
    $core->render('dash', 
        array('title' => 'Administrador'.SITENAME,
            'classb' => 'admin-home',
            'section' => 'admin-home',
            'section_title' => 'Administrador'
        )
    );
});
$core->route('/admin/users', function() use ($core){
    $core->set('flight.views.path', ABSPATH.'core/views');
    $core->checkAuthPermission(array(10));
    $core->render('users', 
        array('title' => 'Usuario'.SITENAME,
          'classb' => 'users',
          'section' => 'users',
          'section_title' => 'Usuarios'
        )
    );
});
// list respurce by id
$core->route('GET /admin/user/@id', function($id) use ($core){
    $core->set('flight.views.path', ABSPATH.'core/views');
    $core->checkAuthPermission(array(10));
    if ($id == 0) {
        $section_title = 'Nuevo Usuario';
        $data = (object) array('user_user'=>'','user_name'=>'','user_email'=>'','user_phone'=>'',
            'user_birthday'=>'','user_level'=>'','user_info'=>'','user_ustate'=>'');
    }else{
        $data = $core->get_row('users',"*","WHERE ID = {$id}");
        $section_title = 'Editar Usuario: '.$data->user_name;
    }
    $core->render('user', 
        array('title' => $section_title.SITENAME,
            'classb' => 'resource',
            'section' => 'resource',
            'section_title' => $section_title,
            'id' => $id,
            'data' => $data
        )
    );
});
$core->route('POST /admin/user/@id', function($id) use ($core){
    $core->checkAuthPermission(array(10));
    $request = $core->request()->data;
    $hashpass = password_hash($request->user_password, PASSWORD_DEFAULT);
    // if 0 create new client
    if ($id == '0') {
            $user_data = array(
                    'user_user' => $request->user_user,
                    'user_password' => $hashpass,
                    'user_name' => $request->user_name,
                    'user_email' => $request->user_email,
                    'user_phone' => $request->user_phone,
                    'user_birthday' => $request->user_birthday,
                    'user_level' => $request->user_level,
                    'user_info' => $request->user_info,
                    'user_ustate' => $request->user_ustate,
            );
             $core->insert_on('users',$user_data);
            $core->notify_helper_add(array('type' => 'success', 'expire' => time()+10, 'created' => time(), 'text' => '<strong>Usuario creado exitosamente!</strong>.'));
           
    }else{ // else update info of user ID = $id
            $user_data = array(
                    'user_name' => $request->user_name,
                    'user_user' => $request->user_user,
                    'user_email' => $request->user_email,
                    'user_phone' => $request->user_phone,
                    'user_birthday' => $request->user_birthday,
                    'user_level' => $request->user_level,
                    'user_info' => $request->user_info,
                    'user_ustate' => $request->user_ustate,
            );
            if (!empty($request->user_password)) {
                $user_data['user_password'] = $hashpass;
            }
            $core->update_on('users',$user_data,array('ID' => $id));
            $core->notify_helper_add(array('type' => 'success', 'expire' => time()+10, 'created' => time(), 'text' => '<strong>Usuario actulizado exitosamente!</strong>.'));
    }
    $core->redirect('/admin/users');
});

$core->route('/admin/out', function() use ($core){
  unset($_SESSION['mcb_user']);
  session_destroy();
  $core->redirect('/admin');
});

$core->route('POST /admin/ajax', function() use ($core){
    $request = $core->request()->data;
    $files = $core->request()->files;
    $action = $request->action;
    switch ( $action ) {
        case 'sign-in-admin':
                $user = $core->get_row('users',"*","WHERE user_user = '{$request->login_user}'");
                if(empty($user)){
                    $data = array( 'state' => 0,'action' => 'show-error','param' => 'Usuario Incorrecto 🤨' );
                    $core->json($data);
                    die();
                }else{
                    if ($user->user_ustate === 0) {
                        $data = array( 'state' => 0,'action' => 'show-error','param' => 'Usuario Inactivo 😒' );
                        $core->json($data);
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
                $core->json($data);
            break;
        case 'delete':
                $core->checkAuthPermission_ajax(array(8,9,10));
                $condval =  explode(':', $request->sqlcondval);
                $core->delete_on($request->sqltable,array($condval[1] => $condval[0]));
                $data = array( 'state' => 0, 'action' => 'delete_item','param' => $request->domresp );
                $core->json($data);
            break;
        default:
                if (function_exists($action)){
                    $action($core);
                }else{
                    $core->json(array('error' => true,'state' => 0,'action' => 'function not defined','param' => null));
                }
            break;
    }
});
