<?php include 'header.php'; 
$portfolio_description = 'description_'.$lang;
$extra_data = unserialize($data->extra_data);
$view_project = $core->get('site_text')[$lang]['view_project'];
$client_project = $core->get('site_text')[$lang]['client_project'];
$date_project = $core->get('site_text')[$lang]['date_project'];
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

?>
<section class="section-base" id="portfolio-single-text">
	<div class="container is-fullwidth">
      <div class="row">
          <div class="col-lg-6 order-md-last portfolio-single-rigt-side">
            <div class="is-sticky">
                <h1 class="size-h2 margin-bottom"><?php echo $data->title; ?></h1>
                <div class="portfolio-services portfolio-services-desktop">
                  <?php
                    if(!empty($extra_data['servicios'])){
                        $servicios = explode(',', $extra_data['servicios']);
                        foreach ($servicios as $key => $value) {
                            echo "<label>".$servicios_text[$lang][$value]."</label>";
                        }
                    }
                  ?>
                </div>
                <div class="w-richtext">
                    <p><?php echo nl2br($data->{$portfolio_description}); ?></p>
                </div>
                <ul class="portfolio-extra-data">
                  <li>
                    <div class="row">
                      <div class="col"><strong><?php echo $client_project; ?></strong></div>
                      <div class="col"><?php echo $extra_data['client']; ?></div>
                    </div>
                  </li>
                  <li>
                    <div class="row">
                      <div class="col"><strong><?php echo $date_project; ?></strong></div>
                      <div class="col">
                        <?php
                          $mes = date('F', strtotime($extra_data['project_date']));
                          $meses['es'] = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                          $meses['en'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                          $meses['pt'] = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
                          $nombreMes = str_replace($meses['en'], $meses[$lang], $mes);
                          $anio = date('Y', strtotime($extra_data['project_date']));
                          echo $nombreMes.', '.$anio; 
                        ?>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="portfolio-services portfolio-services-mobile">
                  <?php
                    if(!empty($extra_data['servicios'])){
                        $servicios = explode(',', $extra_data['servicios']);
                        foreach ($servicios as $key => $value) {
                            echo "<label>".$servicios_text[$lang][$value]."</label>";
                        }
                    }
                  ?>
                </div>
                <?php if (!empty($extra_data['project_url'])) { echo "<a href='".$extra_data['project_url']."' class='mh-button-primary'>".$view_project."</a>";} ?>
            </div>
          </div>
          <div class="col-lg-6 portfolio-single-left-side">
                <div class="multimedia-container">
                    <?php
        				if (!empty($extra_data['multimedia'])) {
        					foreach ($extra_data['multimedia'] as $key => $multimedia) {
        					    $url_multimedia = SITE.'img/portfolio/'.$data->ID.'/'.$multimedia['value'];
        					    if(($multimedia['type'] == 'imagen')){
                                ?>
                                        <img src="<?php echo $url_multimedia; ?>" srcset="<?php echo $url_multimedia; ?> 500w, <?php echo $url_multimedia; ?> 1000w" sizes="(max-width: 991px) 100vw, (max-width: 2272px) 44vw, 1000px" alt="">
                                <?php }else{ ?>
                    					<video width="100%" mute loop autoplay>
                                          <source src="<?php echo $url_multimedia; ?>" type="video/mp4">
                                          Your browser does not support HTML5 video.
                                        </video>
                    			<?php }		    
        					}//end foreach
        				}else{
        				    $prox['es'] = 'Próximamente';
        				    $prox['en'] = 'Coming soon';
        				    $prox['pt'] = 'Em breve';
        				    echo "<h3 class='multimedia-prox'>".$prox[$lang]."</h3>";
        				}
        			?>
                </div>
          </div>
      </div>
	</div>
</section>
<?php include 'footer.php'; ?>

<?php  $extras = array(
  "project_url"=>   "",
  "client"=>   "OBRANDO",
  "project_date"=>  "-",
  "servicios" => "1,10"
    ); ?>


<div style="display:none"><?php echo ($extra_data['multimedia']['media_0']['value'])?></div>