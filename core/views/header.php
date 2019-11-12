<?php 
global $core, $roles;
$db = $core->db();
$prefix = DBPREFIX;
$sidebar_state = (isset($_COOKIE["sidebar"]) && $_COOKIE["sidebar"] == 1 )?  'sideClose' : '' ;
$current_url = ltrim($core->request()->url, '/');

$modules = $core->get('modules');
ksort($modules);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta -->
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="shortcut icon" href="<?php echo SITE; ?>core/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="icon" href="<?php echo SITE; ?>core/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="apple-touch-icon" href="<?php echo SITE; ?>core/assets/img/favicon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo SITE; ?>core/assets/img/favicon.png" />

  <?php
    $core->print_assets(3,true,false,true);
  ?>

  <script>
  	     var base_url = '<?php echo SITE; ?>';
  </script>
</head>
<body class="page <?php echo $classb.' '.$sidebar_state; ?>">
<?php if(!empty($_SESSION['mcb_user'])){ ?>
<div class="sidebar">
    <div class="sidelogo">
      <a href="<?php echo SITE; ?>admin">
        <img src="<?php echo SITE; ?>core/assets/img/mediahaus.svg">
      </a> <button id="toggler_sider"><i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-bars" aria-hidden="true"></i></button>
    </div>
    <div class="sidemenu">
      <ul id="menu_main" class="menu_main">
        <li <?php current_page($section,'admin-home'); ?>>
            <a href="<?php echo SITE; ?>admin"><i class="fa fa-circle-thin" aria-hidden="true"></i> Inicio</a>
        </li>
        <?php
        foreach ($modules as $value) {
            $permiso = false;
            foreach ($value['capability'] as $permisos) {
                if (!$permiso) {
                    $permiso = $_SESSION['mcb_user']['level'] == $permisos ? true : false;
                }
            }
            if ($permiso) { ?>
            <li <?php current_page($section,'module'.$value['module_url']); ?>>
                <a href="<?php echo SITE.'admin/'.$value['module_url']; ?>"><i class="fa fa-circle-thin" aria-hidden="true"></i><?php echo $value['menu_title']; ?></a>
                <?php
                    if(!empty($value['submenu'])):
                ?>
                    <ul class="side_sub_menu">
                        <?php
                        foreach ($value['submenu'] as $url => $submenu) {
                        ?>
                            <li><a href="<?php echo SITE.'admin/'.$value['module_url'].'/'.$url; ?>"><?php echo $submenu; ?></a></li>
                        <?php } ?>
                    </ul>
                    <?php
                    endif; ?>
            </li>
          <?php
            }
        }
          ?>

        <li <?php current_page($section,'users'); ?>>
            <a href="<?php echo SITE; ?>admin/users"><i class="fa fa-circle-thin" aria-hidden="true"></i> Usuarios</a>
        </li>
        <li class="hidden" <?php current_page($section,'configure'); ?>>
            <a href="<?php echo SITE; ?>admin/configure"><i class="fa fa-circle-thin" aria-hidden="true"></i> Configuración</a>
        </li>
        <li>
            <a href="<?php echo SITE; ?>admin/out"><i class="fa fa-circle-thin" aria-hidden="true"></i> Salir</a>
        </li>
      </ul>
    </div>
</div>
<?php } ?>
<div class="content">
  <div id="notify-placeholder" class="container">
    <?php $core->notify_helper(); ?>
  </div>
