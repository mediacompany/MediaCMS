<div class="row">
	<div class="col-sm-6">
		<h1 class="section_title"><?php echo $section_title; ?></h1>
	</div>
	<div class="col-sm-6">
		<a href="<?php echo SITE; ?>/portfolio/0" class="btn btn_new"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Proyecto</a>
	</div>
	<div class="clearfix"></div>
	<div class="clearfix"></div>
	<div class="col-sm-12 media-tables">
		<table class="media-table">
		  <tr>
		    <th>Proyectos</th>
		  </tr>
		  <?php
                foreach ( $app->get_portfolios() as $key => $post) { 
					$datos = unserialize($post->extra_data)?>
                	<tr id="delitem<?php echo $post->ID; ?>">
	                	<td>
							<a href="<?php echo SITE.'/portfolio/'.$post->ID; ?>" class="media-table-title">
								<?php echo $post->title;
								if(!EMPTY ($datos['project_url'])){ 
									echo ' - ' .$datos['project_url'];
								}
								?>
	                		</a>
							<a href="<?php echo SITE.'/portfolio/'.$post->ID; ?>" class="media-table-fecha">
								<?php echo ($datos['client']);
								if(($datos['project_date'])!= '-'){ 
									echo ' - desde ' .$datos['project_date'];
								}
								?>
	                		</a>
							<div class="media-table-actions">
								<a href="<?php echo SITE.'/portfolio/'.$post->ID; ?>" class="media-table-edit-item">
									<i class="fa fa-pencil" aria-hidden="true"></i> Editar
								</a>
								<a href="#" class="media-table-delete-item" data-todelete="<?php echo $post->ID; ?>:ID,posts,delitem<?php echo $post->ID; ?>,¿Deseas eliminar esta Noticia?">
									<i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar
								</a>
							</div>
	                	</td>
                	</tr>
                <?php 
            	}
		  ?>
		</table>
	</div>
</div>
