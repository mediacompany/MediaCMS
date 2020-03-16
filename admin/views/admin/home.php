<?php include 'header.php';
foreach ( $app->get_portfolios() as $key => $portfolio) { 
	if($portfolio->visibility == 0){
		$visibles++;
	}
	else{
		$ocultos++;
	}
}
?>

<h1 class="section_title"><?php echo $section_title; ?></h1>
<div class="row">
	<div class="col-sm-4">
		<div class="white_box info_counter">
			<h3>Proyecto Visibles</h3>
			<h1 class="text-center"><?php echo (!EMPTY($visibles) ) ? $visibles : "0"; ?></h1>		
		</div>
	</div>
	<div class="col-sm-4">
		<div class="white_box info_counter">
			<h3>Proyectos Ocultos</h3>
			<h1 class="text-center"><?php echo (!EMPTY($ocultos) ) ? $ocultos : "0"; ?></h1>		
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>