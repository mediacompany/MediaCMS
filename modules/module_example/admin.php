<div class="row">
	<div class="col-sm-6">
		<h1 class="section_title"><?php echo $section_title; ?></h1>
	</div>
	<div class="col-sm-6">
	</div>
	<div class="clearfix"></div>
	<h1>Example Plugin</h1>
	<?php if($data->ID == 0): ?>
	<h3>Modulo de prueba</h3>
	<div class="col-sm-12">
		<form method="post">
				<label>Update bar_colum</label>
				<input type="text" name="bar_column" class="form-input" required>
				<button type="submit" class="btn btn_primary">Update</button>
		</form>
	</div>
	<?php endif; ?>
</div>
