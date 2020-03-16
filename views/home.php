<?php include 'header.php'; ?>
<section class="section-base" id="big-text">
	<div class="container">
			<p class="text-color-gris"><?php $core->page_text('home_pre_text'); ?></p>
			<h1><?php echo nl2br($core->page_text('home_text',false)); ?></h1>
	</div>
</section>
<?php include 'home-portfolio.php' ?>
<?php include 'footer.php'; ?>