<?php include 'header.php'; ?>
<section class="section-base" id="big-text">
	<div class="container">
			<p class="text-color-gris"><?php $core->page_text('about_pre_text'); ?></p>
			<h1><?php echo nl2br($core->page_text('about_text',false)); ?></h1>
      <p class="about_text1"><?php echo nl2br($core->page_text('about_text1',false)); ?></p>
	</div>
</section>
<section id="about-pictures">
  <div class="container is-fullwidth parallax-window" data-parallax="scroll" data-image-src="<?php echo SITE.'img/about.jpg'; ?>" data-speed="0.1">
      <div class="row no-gutters" hidden>
        <div class="col about-picture"><img src="<?php echo SITE.'img/about.jpg'; ?>"></div>
      </div>
  </div>
</section>
<section class="section-base" id="about-text" hidden>
  <div class="container">
      <p class="about_text1"><?php echo nl2br($core->page_text('about_text2',false)); ?></p>
  </div>
</section>
<?php
// <section id="about-pictures" hidden>
//   <div class="container is-fullwidth">
//       <div class="row no-gutters">
//         <div class="col-lg-4 about-picture"><img src="https://via.placeholder.com/740x1000.png?text=IMAGE 740x1000"></div>
//         <div class="col-lg-4 about-picture"><img src="https://via.placeholder.com/740x1000.png?text=IMAGE 740x1000"></div>
//         <div class="col-lg-4 about-picture"><img src="https://via.placeholder.com/740x1000.png?text=IMAGE 740x1000"></div>
//       </div>
//   </div>
// </section>
?>
<?php include 'footer.php'; ?>