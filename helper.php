<?php
$core->set('flight.log_errors', true);
$core->register('db', 'PDO', array("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER,DBPASS),
    function($db){
	    try {
	        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	        $db->exec("SET NAMES 'utf8mb4';");
	    } catch (PDOException $e) {
	        echo $e->getMessage();
	        die();
	    }
    }
);
require ABSPATH.'core/db-manager.php';
$core->map('check_lang',function($lang) use ($core){
    global $lang_config;
    if (!empty($lang) && in_array($lang, $lang_config )) {
		$core->set('lang', $lang);
	}
});
/* --- To module --- */
$core->map('seo',function($meta) use ($core){
	$lang = $core->get('lang');
	$og_locale = array('es' => 'es_AR', 'en' => 'en_US', 'pt' => 'pt_BR');
    $meta_default = array(
		'title' => SITENAME.' – Default Title',
		'description' => 'Default Description',
		'robots' => 'index,follow',
		'keywords' => 'Default Keywords',
		'author' => 'Default Author',
		'canonical' => SITE,
		'og:url' => SITE,
		'og:image' => SITE.'assets/img/favicon.png',
		'og:type' => 'website',
		'og:title' => SITENAME.' – Default Title',
		'og:description' => 'Default Description',
		'og:site_name' => SITENAME,
		'og:locale' => $og_locale[$lang]
	);
	?>
	<title><?php echo (!empty($meta[$lang]['title'])? $meta[$lang]['title'] : $meta_default['title']); ?></title>
	<meta name="description" content="<?php echo (!empty($meta[$lang]['description'])? $meta[$lang]['description'] : $meta_default['description']); ?>">
	<meta name="robots" content="<?php echo (!empty($meta[$lang]['robots'])? $meta[$lang]['robots'] : $meta_default['robots']); ?>">
	<meta name="keywords" content="<?php echo (!empty($meta[$lang]['keywords'])? $meta[$lang]['keywords'] : $meta_default['keywords']); ?>">       
	<meta name="author" content="<?php echo (!empty($meta[$lang]['author'])? $meta[$lang]['author'] : $meta_default['author']); ?>">
	<meta property="og:url" content="<?php echo (!empty($meta[$lang]['og:url'])? $meta[$lang]['og:url'] : $meta_default['og:url']); ?>">
	<meta property="og:image" content="<?php echo (!empty($meta[$lang]['og:image'])? $meta[$lang]['og:image'] : $meta_default['og:image']); ?>" />
	<meta property="og:type" content="<?php echo (!empty($meta[$lang]['og:type'])? $meta[$lang]['og:type'] : $meta_default['og:type']); ?>">
	<meta property="og:title" content="<?php echo (!empty($meta[$lang]['og:title'])? $meta[$lang]['og:title'] : $meta_default['og:title']); ?>">
	<meta property="og:description" content="<?php echo (!empty($meta[$lang]['og:description'])? $meta[$lang]['og:description'] : $meta_default['og:description']); ?>">
	<meta property="og:site_name" content="<?php echo (!empty($meta[$lang]['og:site_name'])? $meta[$lang]['og:site_name'] : $meta_default['og:site_name']); ?>">
	<meta property="og:locale" content="<?php echo (!empty($meta[$lang]['og:locale'])? $meta[$lang]['og:locale'] : $meta_default['og:locale']); ?>">
	<link rel="canonical" href="<?php echo (!empty($meta[$lang]['canonical'])? $meta[$lang]['canonical'] : $meta_default['canonical']); ?>">
	<?php
});
$core->map('page_text',function($text_key, $echo = true, $lang = null) use ($core){
	if (empty($text_key)) {
		return false;
	}
	$text_key = $text_key.'_'.$core->get('lang');
	if (!empty($lang)) {
		$text_key = $text_key.'_'.$lang;
	}
	$result = $core->get_row('pages','text_content',"WHERE text_key = '{$text_key}'");
	if (!empty($result->error)) {
		$text_content = 'text_key not found';
		return false;
	}
	$text_content = $result->text_content;
	if(!$echo){
		return $text_content;
	}
	echo $text_content;
});
$core->map('notFound', function() use ($core){
    $core->render('404', array('meta' => array(), 'body_class' => 'not-found'));
});
$core->map('translate_url', function() use ($core){
	$current_url = $core->request()->url;
	if ($core->get('is_portfolio')) {
		return (object) array('es' => SITE.'portafolio/'.$core->get('portfolio_slug'),'en' => SITE.'en/portfolio/'.$core->get('portfolio_slug'),'pt' => SITE.'pt/portfolio/'.$core->get('portfolio_slug'));
	}
	switch ($current_url) {
		case '/':
			return (object) array('es' => SITE,'en' => SITE.'en','pt' => SITE.'pt');
			break;
		case '/en':
			return (object) array('es' => SITE,'en' => SITE.'en','pt' => SITE.'pt');
			break;
		case '/pt':
			return (object) array('es' => SITE,'en' => SITE.'en','pt' => SITE.'pt');
			break;
		case '/servicios':
			return (object) array('es' => SITE.'servicios','en' => SITE.'en/services','pt' => SITE.'pt/servicos');
			break;
		case '/en/services':
			return (object) array('es' => SITE.'servicios','en' => SITE.'en/services','pt' => SITE.'pt/servicos');
			break;
		case '/pt/servicos':
			return (object) array('es' => SITE.'servicios','en' => SITE.'en/services','pt' => SITE.'pt/servicos');
			break;
		case '/nosotros':
			return (object) array('es' => SITE.'nosotros','en' => SITE.'en/about','pt' => SITE.'pt/nos');
			break;
		case '/en/about':
			return (object) array('es' => SITE.'nosotros','en' => SITE.'en/about','pt' => SITE.'pt/nos');
			break;
		case '/pt/nos':
			return (object) array('es' => SITE.'nosotros','en' => SITE.'en/about','pt' => SITE.'pt/nos');
			break;
		default:
			return array('en' => SITE.'en','pt' => SITE.'pt');
			break;
	}
});
$core->map('mail',function($to=array(),$subject="Media CMS",$body="<h1>Media CMS</h1>",$reply=array(),$from=array(MAILER_USER => 'Media CMS')) use ($core,$mail){
    try {
        //server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = MAILER_HOST;
        $mail->Port = MAILER_PORT;
        $mail->SMTPSecure = MAILER_SECURE;
        $mail->SMTPAuth = true;
        $mail->Username = MAILER_USER;
        $mail->Password = MAILER_PASS;
        $mail->CharSet = 'UTF-8';
        //Contents
        $mail->Subject = $subject;
        $mail->msgHTML($body);
        $mail->AltBody = strip_tags($body);
        $mail->Body = $body;
        
        // recipients
        $mail->setFrom(MAILER_USER,'Media CMS');
        if(!empty($to)){
            foreach($to as $mailto => $name){
                $mail->addAddress($mailto,$name);
            }
        }
        if(!empty($reply)){
            $mail->addReplyTo(array_keys($reply)[0],$reply[array_keys($reply)[0]]);
        }
        // send mail
        $mail->send();
        return true;
    }catch (Exception $e){
        return (object) array('error' => true, 'msg' => '<strong>Error:</strong> '.$mail->ErrorInfo);
    }
});

function get_data($url, $param){
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}