<?php include 'header.php'; ?>
<div class="col-sm-5 col_login">
	<div class="make_vertical_center">
		<div class="elem_center_vertical">
			<form class="ajax_form">
				<input type="hidden" name="action" value="sign-in">
				<div class="col-sm-12 login_logo">
					 <a href="<?php echo SITE; ?>/">
					 	<img src="<?php echo SITE; ?>/img/mediahausnegro.svg">
					 </a>
				</div>
			    <div class="col-sm-12">
			        <h4><?php echo $section_title; ?></h4>
			    </div>
			    <div class="form-element col-sm-12">
			        <label>Usuario</label>
			        <input type="text" name="login_user" value="" required autocomplete="new-user">
			    </div>
			    <div class="form-element col-sm-12">
			        <label>Contraseña</label>
			        <input type="password" name="login_password" value="" autocomplete="new-password">
			    </div>
			    <div class="form-element col-sm-12 text-center">
			        <a href="#" class="hidden">Olvidé mi contraseña</a>
			    </div>
			    <div class="form-element mt20 col-sm-12">
			        <button type="submit" class="btn btn_primary center-block">Entrar</button>
			    </div>
			</form>
		</div>
	</div>
	<div class="copyright_login">
		&copy; <?php echo date('Y'); ?> MediaCore.
	</div>
</div>
<div class="col-sm-7 bg_login">
	
</div>
<?php include 'footer.php'; ?>