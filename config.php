<?php
define('ABSPATH', trailingslashit(dirname(__FILE__)));
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS',''	);
define('DBNAME','mediahaus');
define('DBPREFIX','mh');
define('SITENAME','MediaHaus');
define('MAILER_USER','no-reply@mediahaus.com.ar');
define('MAILER_PASS','g~}^o{CfwJ,8');
define('MAILER_HOST','mail.mediacore.com.ar');
define('MAILER_PORT','465');
define('MAILER_SECURE','ssl');
$lang_config = array('es','en','pt');
$site_text = array(
	'es' => array(
		'menu' => array(
			'/' => ['Inicio'], 
			'/servicios' => ['Servicios'], 
			'/nosotros' => ['Nosotros'], 
			'/contacto' => ['Contacto','nav-contact-toggler']
		),
		'footer' => array(
			'text' => 'Para consultas o simplemente saludar',
			'text1' => 'Si querés seguir viendo',
			'text2' => 'Av. Cabildo 4769 - Piso 11<br>
               Núñez CABA, Argentina<br>
               <a href="tel:+541147021380">+54 (11) 4702 1380</a><br>',
			'asunto' => 'Contacto desde sitio web'
		),
		'text-404-1' => 'Ups 404',
		'text-404-2' => 'esta página no la encontramos',
		'text-404-3' => 'Ir al Inicio',
		'view_project' => 'VER PROYECTO',
		'client_project' => 'Cliente',
		'date_project' => 'Fecha',
		'send_text' => 'Enviar',
		'form_firstname' => 'Nombre',
		'form_lastname' => 'Apellido',
		'form_mail' => 'Correo eléctronico',
		'form_message' => 'Escribí un mensaje',
		'home_mobile_text' => 'Portafolio'
	),
	'en' => array(
		'menu' => array(
			'/en' => ['Home'], 
			'/en/services' => ['Services'], 
			'/en/about' => ['About us'], 
			'/en/contact' => ['Contact','nav-contact-toggler']
		),
		'footer' => array(
			'text' => 'For inquiries or just say hello',
			'text1' => 'If you want to keep watching',
			'text2' => 'Av. Cabildo 4769 - 11th Floor<br>
               Núñez CABA, Argentina<br>
               <a href="tel:+541147021380">+54 (11) 4702 1380</a><br>',
			'asunto' => 'Contato do site'
		),
		'text-404-1' => 'Ups 404',
		'text-404-2' => 'we can\'t find this page',
		'text-404-3' => 'Go to Home',
		'view_project' => 'SEE PROJECT',
		'client_project' => 'Client',
		'date_project' => 'Date',
		'send_text' => 'Send',
		'form_firstname' => 'First Name',
		'form_lastname' => 'Last Name',
		'form_mail' => 'Email',
		'form_message' => 'Write a message',
		'home_mobile_text' => 'Portfolio'
	),
	'pt' => array(
		'menu' => array(
			'/pt' => ['Casa'], 
			'/pt/servicos' => ['Serviços'], 
			'/pt/nos' => ['Nós'], 
			'/pt/contato' => ['Contato','nav-contact-toggler']
		),
		'footer' => array(
			'text' => 'Para perguntas ou apenas diga olá',
			'text1' => 'Se você quiser continuar assistindo',
			'text2' => 'Av. Cabildo 4769 - 11º andar<br>
               Núñez CABA, Argentina<br>
               <a href="tel:+541147021380">+54 (11) 4702 1380</a><br>',
			'asunto' => 'Contact from website'
		),
		'text-404-1' => 'Ups 404',
		'text-404-2' => 'Não conseguimos encontrar esta página',
		'text-404-3' => 'Ir para casa',
		'view_project' => 'VER PROJETO',
		'client_project' => 'Cliente',
		'date_project' => 'Data',
		'send_text' => 'Enviar',
		'form_firstname' => 'Primeiro nome',
		'form_lastname' => 'Último nome',
		'form_mail' => 'Correio eletrônico',
		'form_message' => 'Escreva uma mensagem',
		'home_mobile_text' => 'Portfolio'
	)
);
$servicios_text = array(
  'es' => array(
    1 => 'CONSULTORÍA',
    2 => 'CONTENIDO MULTIMEDIA',
    3 => 'DESARROLLO WEB',
    4 => 'RENDERIZACIÓN CGI',
    5 => 'APPS',
    6 => 'REALIDAD VIRTUAL',
    7 => 'REDACCIÓN CREATIVA', 
    8 => 'DISEÑO GRÁFICO',
    9 => 'REDES SOCIALES',
    10 => 'IDENTIDAD', 
    11 => 'POSICIONAMIENTO DIGITAL', 
    12 => 'MAILING', 
    13 => 'COMUNICACIONES INTERNAS'
  ),
  'en' => array(
    1 => 'CONSULTANCY',
    2 => 'MULTIMEDIA CONTENT',
    3 => 'WEB DEVELOPMENT',
    4 => 'CGI RENDERING',
    5 => 'APPS',
    6 => 'VIRTUAL REALITY',
    7 => 'CREATIVE WRITING', 
    8 => 'GRAPHIC DESIGN',
    9 => 'SOCIAL MEDIA',
    10 => 'IDENTITY', 
    11 => 'DIGITAL POSITIONING', 
    12 => 'MAILING', 
    13 => 'INTERNAL COMMUNICATIONS'
  ),
  'pt' => array(
    1 => 'CONSULTORIA',
    2 => 'CONTEÚDO MULTIMÍDIA',
    3 => 'DESENVOLVIMENTO WEB',
    4 => 'RENDERIZAÇÃO CGI',
    5 => 'APPS',
    6 => 'REALIDADE VIRTUAL',
    7 => 'ESCRITA CRIATIVA', 
    8 => 'DESIGN GRÁFICO',
    9 => 'REDES SOCIAIS',
    10 => 'IDENTIDADE', 
    11 => 'POSICIONAMIENTO DIGITAL', 
    12 => 'MAILING', 
    13 => 'COMUNICAÇÕES INTERNAS'
  )
);