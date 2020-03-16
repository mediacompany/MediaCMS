<?php 
global $core;
$lang = $core->get('lang');
$current_url = $core->request()->url;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
  <meta name="google-site-verification" content="2VHvNYWLfScDgrTQo62quF3IZ1NerOtkgpaRhsvVo2g" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <?php $core->seo($meta); ?>
  <link rel="shortcut icon" href="<?php echo SITE; ?>assets/img/favicon.png" type="image/vnd.microsoft.icon" />
  <link rel="icon" href="<?php echo SITE; ?>assets/img/favicon.png" type="image/vnd.microsoft.icon" />
  <link rel="apple-touch-icon" href="<?php echo SITE; ?>assets/img/favicon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo SITE; ?>assets/img/favicon.png" />
  <link rel="stylesheet" href="<?php echo SITE; ?>assets/css/main.min.css?v=1.0.2711">
    <script>
  	var base_url = '<?php echo SITE; ?>';
  </script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134523496-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-134523496-1');
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics End -->
</head>
<body class="page <?php echo $body_class; ?>">
<header class="header-main">
  <a href="<?php echo SITE; ?>" class="header-logo">
    <img src="<?php echo SITE; ?>assets/img/logo.svg">
  </a>
  <nav class="header-nav">
    <a href="<?php echo $core->translate_url()->es; ?>" class="<?php echo ($lang == 'es') ? 'nav-active' : '' ; ?>">ES</a>
    <a href="<?php echo $core->translate_url()->en; ?>" class="<?php echo ($lang == 'en') ? 'nav-active' : '' ; ?>">EN</a>
    <a href="<?php echo $core->translate_url()->pt; ?>" class="<?php echo ($lang == 'pt') ? 'nav-active' : '' ; ?>">PT</a>
    <button class="nav-sidebar-toggler">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </nav>
</header>
<nav class="main-nav-sidebar">
    <div class="header-nav nav-lang">
      <a href="<?php echo SITE; ?>" class="<?php echo ($lang == 'es') ? 'nav-active' : '' ; ?>">ES</a>
      <a href="<?php echo SITE; ?>en" class="<?php echo ($lang == 'en') ? 'nav-active' : '' ; ?>">EN</a>
      <a href="<?php echo SITE; ?>pt" class="<?php echo ($lang == 'pt') ? 'nav-active' : '' ; ?>">PT</a>
    </div>
    <div class="center-vertical">
      <div class="center-middle">
        <ul class="nav-menu">
          <?php foreach ($core->get('site_text')[$lang]['menu'] as $key => $value) { 
              $link_clases = '';
              if (!empty($value[1])) {
                $link_clases = $value[1];
              }
              if ($current_url == $key) {
                $link_clases = $link_clases.' nav-sidebar-active';
              }
            ?>
            <li><a href="<?php echo untrailingslashit(SITE).$key; ?>" class="<?php echo $link_clases; ?>"><?php echo $value[0]; ?></a></li>
          <?php } ?>
        </ul>
      </div> 
    </div>
    <div class="nav-extra-info">
        <div class="nav-social">
            <a href="https://www.facebook.com/mediahausargentina" class="footer-icon">
              <span class="icon-facebook"></span>
            </a>
            <a href="https://www.instagram.com/mediahaus_agency" class="footer-icon">
              <span class="icon-instagram"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
            </a>
        </div>
        <div class="nav-text">
          <?php echo nl2br(htmlspecialchars_decode($core->page_text('sidebar_text',false))); ?><br>
          <a href="mailto:hola@mediahaus.com.ar?Subject=<?php echo $core->get('site_text')[$lang]['footer']['asunto']; ?>" class="footer-hi">hola@mediahaus.com.ar</a>
        </div>
    </div>
</nav>
<nav class="main-nav-contact">
  <div class="center-vertical">
    <div class="nav-form center-middle">
      <h1><?php $core->page_text('sidebar_text_contact'); ?></h1>
      <form id="nav-form">
          <?php if($core->get('is_tokko')){ echo '<input type="hidden" name="tokko" value="true">'; } ?>
        <input type="hidden" name="action" value="nav-form">
        <input type="hidden" name="lang" value="<?php echo $lang; ?>">
        <input type="text" name="firstname" placeholder="<?php echo $core->get('site_text')[$lang]['form_firstname']; ?>" required>
        <input type="text" name="lastname" placeholder="<?php echo $core->get('site_text')[$lang]['form_lastname']; ?>" required>
        <input type="mail" name="mail" placeholder="<?php echo $core->get('site_text')[$lang]['form_mail']; ?>" required>
        <textarea name="message" placeholder="<?php echo $core->get('site_text')[$lang]['form_message']; ?>"></textarea>
        <div class="container-captcha">
            <div id="contact-captcha"></div>
        </div>
        <button class="mh-button-primary send-button" disabled><?php echo $core->get('site_text')[$lang]['send_text']; ?></button>
        <div class="nav-form-resp"></div>
        <div class="nav-form-loader">
          <div class="loadersmall"></div>
        </div>
      </form>
    </div>
  </div>
</nav>
<div class="content-area">