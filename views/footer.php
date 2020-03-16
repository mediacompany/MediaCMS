<footer class="footer-main">
	<div class="container">
    <div class="row">
            <div class="col-md-4 footer1">
              <a href="/" class="footer-logo mb-3">
                <img src="<?php echo SITE; ?>assets/img/logo_white.svg">
              </a>
              <p class="footer-text">
                <?php echo $core->get('site_text')[$lang]['footer']['text']; ?> 
                <a href="mailto:hola@mediahaus.com.ar?Subject=<?php echo $core->get('site_text')[$lang]['footer']['asunto']; ?>" class="footer-hi">hola@mediahaus.com.ar</a>
              </p>
            </div>
            <div class="col-md-5 offset-sm-3 footer2">
              <p>
                <?php echo $core->get('site_text')[$lang]['footer']['text1']; ?><br>
                <a href="https://www.facebook.com/mediahausargentina" class="footer-icon" title="Facebook">
                  <span class="icon-facebook"></span>
                </a>
                <a href="https://www.instagram.com/mediahaus_agency" class="footer-icon" title="Instagram">
                  <span class="icon-instagram"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
                </a>
              </p>
              <p class="footer-text">
                <?php echo $core->get('site_text')[$lang]['footer']['text2']; ?>
              </p>
            </div>
    </div>
	</div>
</footer>
</div>
<script src="<?php echo SITE; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/parallax.min.js" defer></script>
<script src="<?php echo SITE; ?>assets/js/main.js?v=1.22" defer></script>
<script async>
//var widgetId1,enabledSubmit=function(t){$(".send-button").prop("disabled",!1)},onloadCallback=function(){document.getElementById("contact-captcha")&&(widgetId1=grecaptcha.render("contact-captcha",{sitekey:"6Le_5YQUAAAAAAtEMV_FlPIf2jnCXnkIwAxSV_oV",callback:enabledSubmit}))};
var widgetId1, widgetId2, enabledSubmit = function(t) { $(".send-button").prop("disabled", !1) }, enabledSubmit2 = function(t) { $(".send-button2").prop("disabled", !1) },onloadCallback = function() {document.getElementById("contact-captcha") && (widgetId1 = grecaptcha.render("contact-captcha", {sitekey: "6Le_5YQUAAAAAAtEMV_FlPIf2jnCXnkIwAxSV_oV",callback: enabledSubmit })); document.getElementById("contact-captcha2") && (widgetId2 = grecaptcha.render("contact-captcha2", {sitekey: "6Le_5YQUAAAAAAtEMV_FlPIf2jnCXnkIwAxSV_oV",callback: enabledSubmit2 })) };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" defer></script>
</body>
</html>