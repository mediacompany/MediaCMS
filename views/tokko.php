<?php include 'header.php'; ?>
<style>
.texto_tokko {
    max-width: 80%;
    margin: 55px 0;
    display: block;
}
</style>
<section class="section-base" id="big-text">
	<div class="container">
			<p class="text-color-gris" hidden><?php $core->page_text('home_pre_text'); ?></p>
			<h1 hidden><?php echo nl2br($core->page_text('home_text',false)); ?></h1>
			<h1>Integramos su web con <img src="<?php echo SITE; ?>assets/img/logo_tokko.png" class="logo_tokko"></h1>
			<p class="text-color-gris texto_tokko">
			    TOKKO BROKER ES EL CRM PARA INMOBILIARIAS DEL MERCADO.
                NOS HA SELECCIONADO COMO AGENCIA PARTNER AUTORIZADO PARA LA INTEGRACIÓN DE NUESTROS DESARROLLOS A SU SISTEMA DE GESTIÓN.
                AUMENTE LA PRODUCTIVIDAD DEL SITIO WEB DE SU INMOBILIARIA Y CONOZCA LA DIFERENCIA.
			</p>
	</div>
</section>
<?php include 'home-portfolio.php' ?>
<?php include 'footer.php'; ?>