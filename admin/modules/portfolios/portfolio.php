<div class="row">
    <div class="col-sm-6">
        <h1 class="section_title">
            <?php echo $section_title;
            $extra=unserialize($data->extra_data);?>
        </h1>
    </div>
    <div class="col-md-12">
        <form method="post">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div class="white_box">
                        <div class="form-element">
                            <label>Titulo</label>
                            <input type="text" name="portfolio_title" value="<?php echo $data->title;?>" id="portfolio_title" required>
                        </div>
                        <div class="form-element">
                            <label>URL del proyecto:</label>
                            <input type="text" name="portfolio_url" value="<?php echo $extra['project_url']; ?>" id="post_slug" class="make_slug_pickup_0010" required>
                        </div>
                        <div class="form-element">
                            <label>Fecha del proyecto:</label>
                            <input type="text" name="portfolio_date" value="<?php echo $extra['project_date'];; ?>" id="post_slug" class="make_slug_pickup_0010" required>
                        </div>
                        <div class="form-element">
                            <label>Cliente:</label>
                            <input type="text" name="portfolio_client" value="<?php echo $extra['client']; ?>" id="post_slug" class="make_slug_pickup_0010" required>
                        </div>
                        <div id="accordion">
                            <label>Descripción:</label>
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Español
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="form-element">
                                            <textarea name="portfolio_description_es" class="post_text"><?php echo $data->description_es; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Ingles
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="form-element">
                                            <textarea name="portfolio_description_en" class="post_text"><?php echo $data->description_en; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Portugues
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="form-element">
                                            <textarea name="portfolio_description_pt" class="post_text"><?php echo $data->description_pt; ?></textarea>
                                         </div>
                                     </div>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <div class="white_box">
                        <div class="form-element">
                            <button type="submit" class="btn btn_primary center-block">Guardar</button>
                        </div>
                        <div class="form-element">
                            <label>Categorías</label>
                            <ul class="list_checkbox">
                                <?php
                                    $categories = $db->prepare("SELECT * FROM {$prefix}_portfolio_categories"); 
                                    $categories->execute();
                                    $post_category = explode(',', $data->category);
                                    foreach ($categories->fetchAll() as $key => $category) { ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="portfolio_category[]" value="<?php echo $category->ID; ?>" 
                                                <?php if(in_array($category->ID, $post_category)) { echo "checked"; } ?> >
                                                <span><?php echo $category->title_es; ?></span>
                                            </label>
                                        </li>
                                    <?php 
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="form-element">
                            <label>Categorías</label>
                            <ul class="list_checkbox">
                                <?php
                                    $categories = $db->prepare("SELECT * FROM {$prefix}_portfolio_service"); 
                                    $categories->execute();
                                    $post_category = explode(',', $extra['servicios']);
                                    foreach ($categories->fetchAll() as $key => $category) { ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="portfolio_service[]" value="<?php echo $category->ID; ?>" 
                                                <?php if(in_array($category->ID, $post_category)) { echo "checked"; } ?> >
                                                <span><?php echo $category->title_es; ?></span>
                                            </label>
                                        </li>
                                    <?php 
                                    }
                                ?>
                            </ul>
                        </div>

                        <div class="form-element">
                            <label>Imagen de portada Grande (1024x768 px.)</label>
                            <input type="hidden" name="portfolio_image" class="portfolio_image" id="post_image_hidden" value="<?php echo ($data->post_image)? $data->post_image : SITEURL.'/../img/portfolio/default-image.png'; ?>">
                            <div class="post_image" style="background-image: url(<?php echo ($extra['cover'])? SITEURL.'/../img/portfolio/'.$extra['cover'] : SITEURL.'/../img/portfolio/default-image.png'; ?>);">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <div class="form-element">
                                <label>Imagen de portada Chica (1024x768 px.)</label>
                                <input type="hidden" name="portfolio_image_small" class="portfolio_image" id="post_image_small_hidden" value="<?php echo ($data->post_image)? $data->post_image : SITEURL.'/../img/portfolio/default-image.png'; ?>">
                                <div class="post_image_small mb-4" style="background-image: url(<?php echo ( $extra['cover-small'])? $data->post_image : SITEURL.'/../img/portfolio/default-image.png'; ?>);">
                            </div>
                        </div>
                            </div>
                        </div>
                        <div class="form-element">
                            <label>Orden</label>
                            <input type="text" name="portfolio_order" value="<?php echo ($data->portfolio_order)? $data->portfolio_order : 0 ; ?>" id="post_order" required>
                        </div>
                        <div class="form-element">
                            <label>Estatus</label>
                            <div class="form-select">
                                <select name="portfolio_visibility">
                                    <option value="1" <?php selected($data->portfolio_visibility,1); ?>>Público</option>
                                    <option value="0" <?php selected($data->portfolio_visibility,0); ?>>Oculto</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-element">
                            <label>Slug</label>
                            <input type="text" name="portfolio_slug" value="<?php echo $data->slug; ?>" id="post_metatags" required>
                        </div>
                        <!-- <div class="form-element" id="post_metadescription_holder">
                            <label>Meta Descripcíon</label>
                            <span class="seocounter cdescrip">160</span>
                            <textarea name="post_metadescription" id="post_metadescription" data-counter="cdescrip" data-cmax="160"><?php echo $data->post_metadescription; ?></textarea>
                        </div> -->
                        <!-- <div class="form-element">
                            <h5>Previsualización</h5>
                            <div class="google_preview">
                                <a class="tituloAzul" href="#" onclick="return false;">Titulo</a>
                                <div class="urlVerde"><?php echo SITEURL; ?>/noticia/<span></span></div>
                                <div class="textoGris"><?php echo $data->post_metadescription; ?></div>
                            </div>
                        </div> -->
                        <div class="form-element">
                            <button type="submit" class="btn btn_primary center-block">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>