<?php 
global $app, $roles;
$db = $app->db();
$prefix = DBPREFIX;
$sidebar_state = (isset($_COOKIE["sidebar"]) && $_COOKIE["sidebar"] == 1 )?  'sideClose' : '' ;
$current_url = ltrim($app->request()->url, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/plugins/trumbowyg.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/plugins/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/plugins/datepicker.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/plugins/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/plugins/multiple-select.css">
  <link rel="stylesheet" href="<?php echo SITE; ?>/css/admin/style.css">
  <link rel="shortcut icon" href="<?php echo SITE; ?>/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="icon" href="<?php echo SITE; ?>/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="apple-touch-icon" href="<?php echo SITE; ?>/img/favicon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo SITE; ?>/img/favicon.png" />
  <script>
  	     var base_url = '<?php echo SITE; ?>';
  </script>
</head>
<body class="page <?php echo $classb.' '.$sidebar_state; ?>">
<?php if(!empty($_SESSION['mcb_user'])){ ?>
<div class="sidebar">
    <div class="sidelogo">
      <a href="<?php echo SITE; ?>/">
        <img src="<?php echo SITE; ?>/img/mediahaus.svg">
      </a> <button id="toggler_sider"><i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-bars" aria-hidden="true"></i></button>
    </div>
    <div class="sidemenu">
      <ul id="menu_main" class="menu_main">
        <li <?php current_page($section,'admin-home'); ?>>
            <a href="<?php echo SITE; ?>/"><i class="icon-home" aria-hidden="true"></i> Inicio</a>
        </li>
        <?php
        $modules = $app->get('modules');
        ksort($modules);
          foreach ($modules as $key => $module) { ?>
              <li <?php echo current_page(rtrim($current_url, '/'), $module['admin-url']); ?>>
                <a href="<?php echo SITE.'/'.$module['admin-url']; ?>/">
                <i class="icon-<?php echo strtolower($module['name']); ?>" aria-hidden="true"></i> <?php echo $module['name']; ?></a>
                <?php
                  if (!empty($module['submenu'])) {
                    echo "<ul class='side_sub_menu'>";
                    foreach ($module['submenu'] as $key => $value) { ?>
                      <li>
                        <a href="<?php echo SITE.'/'.$key; ?>/"><?php echo $value; ?></a>
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
            <a href="<?php echo SITE; ?>/users"><i class="icon-users" aria-hidden="true"></i> Usuarios</a>
        </li>
        <li class="hidden" <?php current_page($section,'configure'); ?>>
            <a href="<?php echo SITE; ?>/configure"><i class="icon-config" aria-hidden="true"></i> Configuración</a>
        </li>
        <li>
            <a href="<?php echo SITE; ?>/out"><i class="icon-logout" aria-hidden="true"></i> Salir</a>
        </li>
      </ul>
    </div>
</div>
<?php } ?>
<div class="content">
