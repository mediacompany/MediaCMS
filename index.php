<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
// load basic functions and config
require 'core/functions.php';
require 'config.php';
if (!defined('SITE')) {
    define('SITE', trailingslashit(
        ((!empty($_SERVER['HTTPS']))? 'https://' : 'http://').$_SERVER['HTTP_HOST'].((dirname($_SERVER['PHP_SELF'])=='/') ? '' : dirname($_SERVER['PHP_SELF'])))
    );
}
// load composer
require ABSPATH.'vendor/autoload.php';
use flight\Engine;
use PHPMailer\PHPMailer\PHPMailer;
// instance $core scope
$core = new Engine();
$mail = new PHPMailer();
$core->set('lang', 'es');
$core->set('is_portfolio', false);
$core->set('site_text', $site_text);
require 'helper.php';
$meta = array(
		'es' => array(
			'title' => SITENAME.' – Agencia digital especializada en Real Estate',
			'description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
			'keywords' => 'pagina web para inmobiliarias,agencia de marketing inmobiliario,marketing digital buenos aires,Marketing inmobiliario,publicidad para inmobiliarias,marketing web,consultoria marketing,diseño web responsive,TOUR VIRTUAL 360,APLICACIONES MOBILE,inmobiliaria, inmobiliario, inmobiliarias,CREACIÓN DE CONTENIDOS,REALIDAD VIRTUAL,publicidad inmobiliaria,diseño grafico ,marketing argentina,expertos en seo,expertos en diseño,marketing digital para inmobiliarias,plataformas ecommerce,ecommerce argentinabranding,marketing digital real estate,manejo de redes sociales,agencia digital,Real estate,real estate media agency',
		    'author' => SITENAME,
		    'og:site_name' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:title' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
		    'canonical' => 'https://mediahaus.com.ar/'
		),
		'en' => array(
			'title' => SITENAME.' – Agencia digital especializada en Real Estate',
			'description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
			'keywords' => 'pagina web para inmobiliarias,agencia de marketing inmobiliario,marketing digital buenos aires,Marketing inmobiliario,publicidad para inmobiliarias,marketing web,consultoria marketing,diseño web responsive,TOUR VIRTUAL 360,APLICACIONES MOBILE,inmobiliaria, inmobiliario, inmobiliarias,CREACIÓN DE CONTENIDOS,REALIDAD VIRTUAL,publicidad inmobiliaria,diseño grafico ,marketing argentina,expertos en seo,expertos en diseño,marketing digital para inmobiliarias,plataformas ecommerce,ecommerce argentinabranding,marketing digital real estate,manejo de redes sociales,agencia digital,Real estate,real estate media agency',
		    'author' => SITENAME,
		    'og:site_name' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:title' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
		    'canonical' => 'https://mediahaus.com.ar/'
		),
		'pt' => array(
			'title' => SITENAME.' – Agencia digital especializada en Real Estate',
			'description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
			'keywords' => 'pagina web para inmobiliarias,agencia de marketing inmobiliario,marketing digital buenos aires,Marketing inmobiliario,publicidad para inmobiliarias,marketing web,consultoria marketing,diseño web responsive,TOUR VIRTUAL 360,APLICACIONES MOBILE,inmobiliaria, inmobiliario, inmobiliarias,CREACIÓN DE CONTENIDOS,REALIDAD VIRTUAL,publicidad inmobiliaria,diseño grafico ,marketing argentina,expertos en seo,expertos en diseño,marketing digital para inmobiliarias,plataformas ecommerce,ecommerce argentinabranding,marketing digital real estate,manejo de redes sociales,agencia digital,Real estate,real estate media agency',
		    'author' => SITENAME,
		    'og:site_name' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:title' => SITENAME.' - Agencia digital especializada en Real Estate',
		    'og:description' => 'Ofrecemos servicios digitales para el mercado inmobiliario. Producimos Video y Fotografía de propiedades, sitios web, diseño gráfico integral y más. Una agencia, todas las soluciones.',
		    'canonical' => 'https://mediahaus.com.ar/'
		)
);
$core->route('/', function() use ($core,$meta){
    $core->render('home', array('meta' => $meta, 'body_class' => 'home'));
});

$core->route('/en', function() use ($core,$meta){
	$core->set('lang', 'en');
    $core->render('home', array('meta' => $meta, 'body_class' => 'home'));
});

$core->route('/pt', function() use ($core,$meta){
	$core->set('lang', 'pt');
    $core->render('home', array('meta' => $meta, 'body_class' => 'home'));
});
$core->route('/nosotros', function() use ($core,$meta){
	$core->render('about', array('meta' => $meta, 'body_class' => 'about'));
});
$core->route('/en/about', function() use ($core,$meta){
	$core->set('lang', 'en');
	$core->render('about', array('meta' => $meta, 'body_class' => 'about'));
});
$core->route('/pt/nos', function() use ($core,$meta){
	$core->set('lang', 'pt');
	$core->render('about', array('meta' => $meta, 'body_class' => 'about'));
});
$core->route('/servicios', function() use ($core,$meta){
	$core->render('services', array('meta' => $meta, 'body_class' => 'services'));
});
$core->route('/en/services', function() use ($core,$meta){
	$core->set('lang', 'en');
	$core->render('services', array('meta' => $meta, 'body_class' => 'services'));
});
$core->route('/pt/servicos', function() use ($core,$meta){
	$core->set('lang', 'pt');
	$core->render('services', array('meta' => $meta, 'body_class' => 'services'));
});
$core->route('/portafolio(/@portafolio)', function($portafolio) use ($core,$meta){
	$lang = 'es';
	$core->set('is_portfolio', true);
	$core->set('portfolio_slug', $portafolio);
	$core->check_lang($lang);
	$result = $core->get_row('portfolio','*',"WHERE slug = '{$portafolio}'");
    if (!empty($result->error) || !$result) {
    	$core->notFound();
    }
    $core->render('portfolio-single', array('meta' => $meta, 'body_class' => 'portfolio-single', 'data' => $result));
});
$core->route('/en/portfolio(/@portafolio)', function($portafolio) use ($core,$meta){
	$lang = 'en';
	$core->set('is_portfolio', true);
	$core->set('portfolio_slug', $portafolio);
	$core->check_lang($lang);
	$result = $core->get_row('portfolio','*',"WHERE slug = '{$portafolio}'");
    if (!empty($result->error) || !$result) {
    	$core->notFound();
    }
    $core->render('portfolio-single', array('meta' => $meta, 'body_class' => 'portfolio-single', 'data' => $result));
});
$core->route('/pt/portfolio(/@portafolio)', function($portafolio) use ($core,$meta){
	$lang = 'pt';
	$core->set('is_portfolio', true);
	$core->set('portfolio_slug', $portafolio);
	$core->check_lang($lang);
	$result = $core->get_row('portfolio','*',"WHERE slug = '{$portafolio}'");
    if (!empty($result->error) || !$result) {
    	$core->notFound();
    }
    $core->render('portfolio-single', array('meta' => $meta, 'body_class' => 'portfolio-single', 'data' => $result));
});
$core->route('/tokko', function() use ($core,$meta){
    $core->set('is_tokko', true);
	$core->render('tokko', array('meta' => $meta, 'body_class' => 'home tokko'));
});

$core->route('/support', function($id) use ($core,$meta){
    //$core->set('is_tokko', true);
	$core->render('support', array('meta' => $meta, 'body_class' => 'home support'));
});

//$core->route('/editar', function() use ($core){
//	$core->render('editar');
//});
//$core->route('/editar-proyecto(/@id)', function($id) use ($core){
//	$core->render('editar2', array('id'=>$id));
//});

$core->route('POST /ajax', function() use ($core){
    $post = $core->request()->data;
    $recaptcha = $_POST["g-recaptcha-response"];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => '6Le_5YQUAAAAAGWwO7E1eh-1_C6TAt6oc2l9P0i2',
        'response' => $recaptcha
    );
    $verify = get_data($url,$data);
    $captcha_success = json_decode($verify);
    $ip = $navegador = 'en desarrollo';
    $fecha = date('d/m/Y - H:i:s');
    $body = "
        <b>Nombre:</b> {$post->firstname} <br>
        <b>Apellido:</b> {$post->lastname} <br>
        <b>Email:</b> {$post->mail} <br>
        <b>Mensaje:</b> {$post->message} <br>
        <b>Idioma:</b> {$post->lang} <br>
        <b>Fecha:</b> {$fecha} <br>
        <b>IP:</b> {$ip} <br>
        <b>Navegador:</b> {$navegador} <br>
    ";
    $cliente = array($post->mail => $post->firstname.' '.$post->lastname);
    $resp['es'] = 'Su mensaje se ha enviado exitosamente';
    $resp['en'] = 'Your message has been sent successfully';
    $resp['pt'] = 'Sua mensagem foi enviada com sucesso';
    $robot['es'] = 'Por favor verifica que no eres un robot';
    $robot['en'] = 'Please verify that you are not a robot';
    $robot['pt'] = 'Por favor, verifique se você não é um robô';
	if ($captcha_success->success) {
	    //,'jose.marin@mediahaus.com.ar' => 'Jose'
	    $core->mail(array('hola@mediahaus.com.ar' => 'MediaHaus'),'Contacto Sitio Web #'.time(),$body,$cliente,$cliente);
        echo $resp[$post->lang];
    }else{
        echo $robot[$post->lang];
    }
	die;
});

$core->start();