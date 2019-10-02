<?php 
global $core;
$current_url = ltrim($core->request()->url, '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Meta -->
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />

  <!-- Others -->
  <link rel="shortcut icon" href="<?php echo SITE; ?>/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="icon" href="<?php echo SITE; ?>/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="apple-touch-icon" href="<?php echo SITE; ?>/assets/img/favicon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo SITE; ?>/assets/img/favicon.png" />

  <script>
  	     var base_url = '<?php echo SITE; ?>';
  </script>

  <!-- CSS Files -->
  <?php $core->header_front(); ?>


</head>
<body class="page <?php echo $classb; ?>">
<?php //current_page(rtrim($current_url, '/'), ''); ?>