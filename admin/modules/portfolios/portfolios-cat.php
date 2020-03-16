<?php
if (!empty($_POST['category_name']) && empty($_GET['cat'])) {
    $faq_in = $db->prepare("INSERT INTO `{$prefix}_posts_category`(`category_name`,`category_slug`,`category_order`) VALUES ('".$_POST['category_name']."','".$_POST['category_slug']."','".$_POST['category_order']."')"); 
    $faq_in->execute();
}
if (!empty($_POST['category_name']) && !empty($_GET['cat'])) {
    $faq_in = $db->prepare("UPDATE `{$prefix}_posts_category` SET `category_name` = '".$_POST['category_name']."', `category_order` = ".$_POST['category_order'].", `category_slug`= '".$_POST['category_slug']."' WHERE ID = ".$_GET['cat']); 
    $faq_in->execute();
}
if (isset($_GET['cat'])) {
    $faq = $db->prepare("SELECT * FROM {$prefix}_posts_category WHERE ID = ".$_GET['cat']); 
    $faq->execute();
    $data = $faq->fetchAll()[0];            
}else{
    $data = (object) array('category_name' => '', 'category_order' => '', 'category_slug' => '');
}

?>
<div class="row">
    <div class="col-sm-6">
        <h1 class="section_title">
            <?php echo $section_title; ?>
        </h1>
    </div>
    <div class="col-md-offset-2 col-md-8">
        <div class="white_box">
            <form method="post">
                <div class="form-element">
                    <label>Titulo</label>
                    <input type="text" name="category_name" value="<?php echo $data->category_name; ?>" id="category_name" class="make_slug" data-pickup="0011" required>
                </div>
                <div class="form-element">
                    <label>slug</label>
                    <input type="text" name="category_slug" value="<?php echo $data->category_slug; ?>" id="category_slug" class="make_slug_pickup_0011">
                </div>
                <div class="form-element">
                    <label>Orden</label>
                    <input type="number" name="category_order" id="category_order" min="0" value="<?php echo (!empty($data->category_order))? $data->category_order : '0' ; ?>">
                </div>
                <div class="form-element">
                    <button type="submit" class="btn btn_primary center-block">Guardar</button>
                </div>
            </form>
            <div class="row mt20">                
                <div class="col-sm-12 media-tables">
                    <table class="media-table">
                      <tr>
                        <th>Título</th>
                        <th>Orden</th>
                        <th>Slug</th>
                      </tr>
                      <?php
                            $faqs = $db->prepare("SELECT * FROM {$prefix}_posts_category"); 
                            $faqs->execute();
                            foreach ($faqs->fetchAll() as $key => $faq) { ?>
                                <tr id="delitem<?php echo $faq->ID; ?>">
                                    <td>
                                        <a href="<?php echo SITE.'/posts/category/?cat='.$faq->ID;  ?>" class="media-table-title">
                                            <?php echo $faq->category_name; ?>
                                        </a>
                                        <div class="media-table-actions">
                                            <a href="<?php echo SITE.'/posts/category/?cat='.$faq->ID; ?>" class="media-table-edit-item">
                                                <i class="fa fa-pencil" aria-hidden="true"></i> Editar
                                            </a>
                                            <a href="#" class="media-table-delete-item" data-todelete="<?php echo $faq->ID; ?>:ID,posts_category,delitem<?php echo $faq->ID; ?>,¿Deseas eliminar esta Categoria de Novedad?">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar
                                            </a>
                                        </div>
                                    </td>
                                    <td><?php echo $faq->category_order; ?></td>
                                    <td><?php echo $faq->category_slug; ?></td>
                                </tr>
                            <?php 
                            }
                      ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>