<div class="row">
    <div class="col-sm-6">
        <h1 class="section_title">
            <?php echo $section_title; ?>
        </h1>
    </div>
    <div class="col-md-12">
        <form method="post">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div class="white_box">
                        <div class="form-element">
                            <label>Titulo</label>
                            <input type="text" name="post_title" value="<?php echo $data->post_title; ?>" id="post_title" class="make_slug" data-pickup="0010" required>
                        </div>
                        <div class="form-element">
                            <label>URL:</label>
                            <input type="text" name="post_slug" value="<?php echo $data->post_slug; ?>" id="post_slug" class="make_slug_pickup_0010" required>
                        </div>
                        <div class="form-element">
                            <textarea name="post_text" id="post_text"><?php echo $data->post_text; ?></textarea>
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
                                    $categories = $db->prepare("SELECT * FROM {$prefix}_posts_category"); 
                                    $categories->execute();
                                    $post_category = explode(',', $data->post_category);
                                    foreach ($categories->fetchAll() as $key => $category) { ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="post_category[]" value="<?php echo $category->ID; ?>" 
                                                <?php if(in_array($category->ID, $post_category)) { echo "checked"; } ?> >
                                                <span><?php echo $category->category_name; ?></span>
                                            </label>
                                        </li>
                                    <?php 
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="form-element">
                            <label>Imagen de portada (1024x768 px.)</label>
                            <input type="hidden" name="post_image" id="post_image_hidden" value="<?php echo ($data->post_image)? $data->post_image : SITEURL.'/imgs/default-image.jpg'; ?>">
                            <div class="post_image" style="background-image: url(<?php echo ($data->post_image)? $data->post_image : SITEURL.'/imgs/default-image.jpg'; ?>);">
                                
                            </div>
                        </div>
                        <div class="form-element">
                            <label>Orden</label>
                            <input type="text" name="post_order" value="<?php echo ($data->post_order)? $data->post_order : 0 ; ?>" id="post_order" required>
                        </div>
                        <div class="form-element">
                            <label>Estatus</label>
                            <div class="form-select">
                                <select name="post_status">
                                    <option value="1" <?php selected($data->post_status,1); ?>>Público</option>
                                    <option value="2" <?php selected($data->post_status,2); ?>>Borrador</option>
                                    <option value="3" <?php selected($data->post_status,3); ?>>Privado</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-element">
                            <label>Tags</label>
                            <input type="text" name="post_metatags" value="<?php echo $data->post_metatags; ?>" id="post_metatags" required>
                        </div>
                        <div class="form-element" id="post_metadescription_holder">
                            <label>Meta Descripcíon</label>
                            <span class="seocounter cdescrip">160</span>
                            <textarea name="post_metadescription" id="post_metadescription" data-counter="cdescrip" data-cmax="160"><?php echo $data->post_metadescription; ?></textarea>
                        </div>
                        <div class="form-element">
                            <h5>Previsualización</h5>
                            <div class="google_preview">
                                <a class="tituloAzul" href="#" onclick="return false;">Titulo</a>
                                <div class="urlVerde"><?php echo SITEURL; ?>/noticia/<span></span></div>
                                <div class="textoGris"><?php echo $data->post_metadescription; ?></div>
                            </div>
                        </div>
                        <div class="form-element">
                            <button type="submit" class="btn btn_primary center-block">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>