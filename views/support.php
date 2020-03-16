<?php include 'header.php'; ?>
<style>
#support .nav-form{
    padding:0;
}
.nav-form input, .nav-form textarea {
    color: #000;
}
.nav-form select {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 1px solid var(--color7);
    margin-bottom: 10px;
    line-height: 40px;
    outline: none;
    border-radius: 0;
    -webkit-appearance: none;
}
.select-wrap{
    position:relative;
}
.select-wrap:after{
    content:"▼";
    display:block;
    position:absolute;
    right:10px;
    top:10px;
}
</style>
<section class="section-base" id="support">
	<div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>
                  Soporte MediaHaus
                  <?php //echo $core->get('site_text')[$lang]['text-404-1']; ?>
                </h1>
                <p>
        		    Por favor dinos tu inconveniente, dejándonos los datos en este formulario, te contactaremos a la brevedad posible.
        		</p>
        		<div class="nav-form">
            		<form id="support-form">
                        <input type="hidden" name="action" value="support-form">
                        <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                        <input type="text" name="firstname" placeholder="Nombre" required="">
                        <input type="text" name="lastname" placeholder="Apellido" required="">
                        <input type="mail" name="mail" placeholder="Correo eléctronico" required="">
                        <input type="text" name="web" placeholder="Sitio web">
                        <div class="select-wrap">
                        <select name="dpto" required>
                            <option value="">Área</option>
                            <option value="desarrollo">Desarrollo</option>
                            <option value="hosting">Hosting</option>
                            <option value="ventas">Ventas</option>
                            <option value="rrss">RRSS</option>
                        </select>
                        <div class="select-wrap">
                        <select name="prioridad" required>
                            <option value="">Priodiad</option>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                        </div>
                        <textarea name="message" placeholder="Escribí tu consulta"></textarea>
                        <div class="container-captcha">
                            <div id="contact-captcha2"></div>
                        </div>
                        <button class="mh-button-primary send-button2" disabled="">Enviar</button>
                        <div class="nav-form-resp"></div>
                        <div class="nav-form-loader">
                          <div class="loadersmall"></div>
                        </div>
                    </form>
        		</div>
            </div>
        </div>
	</div>
</section>

<?php include 'footer.php'; ?>