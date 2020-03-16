<?php include 'header.php'; ?>
<section class="section-base" id="big-text">
	<div class="container">
			<p class="text-color-gris"><?php $core->page_text('service_pre_text'); ?></p>
			<h1><?php echo nl2br($core->page_text('service_text',false)); ?></h1>
      <p class="about_text1" hidden><?php echo nl2br($core->page_text('service_text1',false)); ?></p>
	</div>
</section>
<section>
  <div class="container is-fullwidth parallax-window" data-parallax="scroll" data-image-src="<?php echo SITE.'img/services.jpg'; ?>" data-speed="0.1">
      <div class="row no-gutters" hidden>
        <div class="col service-picture"><img src="<?php echo SITE; ?>img/services-banner.png"></div>
      </div>
  </div>
</section>
<section class="section-base" id="services-text">
  <div class="container">
      <div class="row">
          <div class="col-md-3">
              <h3 class="services-title">Servicios</h3>
          </div>
          <div class="col-md-9">
              <ul class="services-list">
                <?php for ($i=1; $i < 14; $i++) {?>
                    <li>
                        <h2><?php echo ucfirst($core->page_text('service_title'.$i,false)); ?></h2>
                        <p><?php echo nl2br($core->page_text('service_content'.$i,false)); ?></p>
                    </li>
                <?php } ?>
              </ul>
          </div>
      </div>
  </div>
</section>
<section id="services-text2">
  <div class="container">
        <h1><?php echo nl2br($core->page_text('service_text2',false)); ?></h1>
        <a href="#" class="mh-button-primary"><?php echo nl2br($core->page_text('service_text3',false)); ?></a>
  </div>
</section>
<?php include 'footer.php'; ?>