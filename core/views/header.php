<?php 
global $core, $roles;
$db = $core->db();
$prefix = DBPREFIX;
$sidebar_state = (isset($_COOKIE["sidebar"]) && $_COOKIE["sidebar"] == 1 )?  'sideClose' : '' ;
$current_url = ltrim($core->request()->url, '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="<?php echo SITE; ?>core/assets/plugins/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>core/assets/plugins/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>core/assets/plugins/datepicker.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>core/assets/plugins/multiple-select.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>core/assets/main.css">
  <link rel="shortcut icon" href="<?php echo SITE; ?>core/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="icon" href="<?php echo SITE; ?>core/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="apple-touch-icon" href="<?php echo SITE; ?>core/assets/img/favicon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo SITE; ?>core/assets/img/favicon.png" />
  <?php $core->header(); ?>
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
        $modules = $core->get('modules');
        ksort($modules);
          foreach ($modules as $key => $module) { ?>
              <li <?php current_page(rtrim($current_url, '/'), $module['admin-url']); ?>>
                <a href="<?php echo SITE.'admin/'.$module['admin-url']; ?>/">
                <i class="fa fa-circle-thin" aria-hidden="true"></i> <?php echo $module['name']; ?></a>
                <?php
                  if (!empty($module['submenu'])) {
                    echo "<ul class='side_sub_menu'>";
                    foreach ($module['submenu'] as $key => $value) { ?>
                      <li>
                        <a href="<?php echo SITE.'admin/'.$module['admin-url'].'/'.$key; ?>/"><?php echo $value; ?></a>
                      </li>
                    <?php }
                    echo "</ul>";
                  }
                ?>
              </li>
            <?php
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
