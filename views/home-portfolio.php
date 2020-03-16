<section class="section-base" id="home-portafolio">
	<div class="container">
	  <div class="col lg-12 mb-5">
	    <div class="filter-menu">
        <a data-category="all" href="#" class="button button--is-active js-button-filter w-button all-filter">all</a>
        <?php 
          $filter_menu = $core->get_all('portfolio_categories');
              foreach ($filter_menu as $key => $filter) { 
                  $filter_name = 'title_'.$lang;
                ?>
                  <a data-category="has-cat<?php echo $filter->ID; ?>" href="#" class="js-button-filter button w-button"><?php echo strtoupper($filter->{$filter_name}); ?><span class="reset-filter icon-x"></span></a>
        <?php } ?>
        <div class="filter-menu-mobile" hidden><?php echo $core->get('site_text')[$lang]['home_mobile_text']; ?></div>
        <?php /* ?>
        
          <select class="filter-menu-select">
              <option value="all">TODAS</option>
              <?php 
                    foreach ($filter_menu as $key => $filter) { 
                        $filter_name = 'title_'.$lang;
                      ?>
                      <option value="has-cat<?php echo $filter->ID; ?>"><?php echo strtoupper($filter->{$filter_name}); ?></option>
              <?php } ?>
          </select>
        </div>
        <?php */ ?>
	  </div>
	 </div>
  </div>
	<div class="col lg-12 portfolio-grid">
		  <div class="w-layout-grid c-portfolio3col is-filterable-grid">
          <?php 
            $portfolios = $core->get_all('portfolio','*','WHERE portfolio_visibility = 1 ORDER BY portfolio_order ASC');
                foreach ($portfolios as $key => $portfolio) { 
                    $portfolio_subtitle = 'sub_title_'.$lang;
                    $portfolio_url = $portfolio->slug;
                    $extra_data = unserialize($portfolio->extra_data);
                    switch ($lang) {
                      case 'en':
                        $portfolio_url = 'en/portfolio/'.$portfolio_url;
                        break;
                      case 'pt':
                        $portfolio_url = 'pt/portfolio/'.$portfolio_url;
                        break;
                      default:
                          $portfolio_url = 'portafolio/'.$portfolio_url;
                        break;
                    }
                    $ext = '.jpg';
                    if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
                        // webp is supported!
                        if(file_exists(ABSPATH.'img/portfolio/'.$portfolio->ID.'/portada.webp')){
                            $ext = '.webp';
                        }
                    }
                    $portfolio_thumb2X = SITE.'img/portfolio/'.$portfolio->ID.'/Portada_grande'. $ext ;
                    //$portfolio_thumb2X = SITE.'img/portfolio/'.$extra_data['cover'] ;
                    $portfolio_thumb = SITE.'img/portfolio/'.$extra_data['cover-small'] ;
                    $serv_num = 0;
                    $serv = '';
                  ?>
                      <a data-category="has-cat<?php echo $portfolio->category; ?>" href="<?php echo SITE.$portfolio_url; ?>" class="c-grid-item card w-inline-block">
                        <div class="c-grid-item__text is-dark">
                          <div class="overflow-hidden">
                            <div class="c-grid-item__title"><?php echo $portfolio->title; ?></div>
                          </div>
                          <div class="overflow-hidden">
                            <div class="c-grid-item__categories">
                                <?php //echo $portfolio->{$portfolio_subtitle}; ?>
                                <?php
                                    if(!empty($extra_data['servicios'])){
                                        global $servicios_text;
                                        $servicios = explode(',', $extra_data['servicios']);
                                        foreach($servicios as $serkey => $servicio){
                                            if($serkey <= 2){
                                                $serv .= $servicios_text[$lang][$servicio].", ";
                                            }
                                        }
                                        echo substr($serv, 0, -2);
                                    }
                                ?>
                            </div>
                          </div>
                        </div>
                        <?php /*
                        <img src="<?php echo $portfolio_thumb2X; ?>" srcset="<?php echo $portfolio_thumb; ?> 500w,<?php echo $portfolio_thumb2X; ?> 700w" sizes="(max-width: 479px) 90vw, (max-width: 752px) 93vw, 700px" alt="" class="c-grid-item__image">
                        */ ?>
                        <img srcset="<?php echo $portfolio_thumb; ?> 320w,<?php echo $portfolio_thumb2X; ?> 1200w" sizes="(max-width: 479px) 90vw, (max-width: 752px) 93vw, 700px" src="<?php echo $portfolio_thumb2X; ?>" alt="" class="c-grid-item__image">

          <?php } ?>
      </div>
  </div>
</section>