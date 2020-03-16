<div class="row">
	<div class="col-sm-6">
		<h1 class="section_title"><?php echo $section_title; ?></h1>
	</div>
	<div class="col-sm-6">
		<a href="<?php echo SITE; ?>/post/0" class="btn btn_new"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Post</a>
	</div>
	<div class="clearfix"></div>
	<form method="GET" class="form-filter">
		<div class="col-sm-3">
			<label>Categoría</label>
			<div class="form-select">
				<select name="bycategory">
					<option value="all">Todas</option>
						<?php
							$categories = $db->prepare("SELECT * FROM {$prefix}_posts_category"); 
			                $categories->execute();
			                foreach ($categories->fetchAll() as $key => $category) { ?>
			                	<option value="<?php echo $category->ID; ?>" <?php selected($app->request()->query['bycategory'],$category->ID); ?>><?php echo $category->category_name; ?></option>
			                <?php 
			            	}
					  	?>
				</select>	
			</div>
		</div>
		<div class="col-sm-2">
			<label>Mes</label>
			<div class="form-select">
				<?php $bymonth = $app->request()->query['bymonth']; ?>
				<select name="bymonth">
					<option value="all" <?php selected('all',$bymonth); ?>>Todos</option>
					<option value="01" <?php selected('01',$bymonth); ?>>Enero</option>
					<option value="02" <?php selected('02',$bymonth); ?>>Febrero</option>
					<option value="03" <?php selected('03',$bymonth); ?>>Marzo</option>
					<option value="04" <?php selected('04',$bymonth); ?>>Abril</option>
					<option value="05" <?php selected('05',$bymonth); ?>>Mayo</option>
					<option value="06" <?php selected('06',$bymonth); ?>>Junio</option>
					<option value="07" <?php selected('07',$bymonth); ?>>Julio</option>
					<option value="08" <?php selected('08',$bymonth); ?>>Agosto</option>
					<option value="09" <?php selected('09',$bymonth); ?>>Septiembre</option>
					<option value="10" <?php selected('10',$bymonth); ?>>Octubre</option>
					<option value="11" <?php selected('11',$bymonth); ?>>Noviembre</option>
					<option value="12" <?php selected('12',$bymonth); ?>>Diciembre</option>
				</select>	
			</div>
		</div>
		<div class="col-sm-2">
			<label>Año</label>
			<div class="form-select">
				<?php $byyear = $app->request()->query['byyear']; ?>
				<select name="byyear">
					<option value="all">Todos</option>
					<?php foreach (array(19,20,21,22,23,24,25,26,27,28,29,19) as $value) { ?>
						<option value="<?php echo $value; ?>" <?php selected($value,$byyear); ?>>20<?php echo $value; ?></option>
					<?php } ?>
				</select>	
			</div>
		</div>
		<div class="col-sm-2">
			<label>Orden</label>
			<div class="form-select">
				<select name="order">
					<option value="DESC" <?php selected($app->request()->query['order'],'DESC'); ?>>DESC</option>
					<option value="ASC" <?php selected($app->request()->query['order'],'ASC'); ?>>ASC</option>
				</select>	
			</div>
		</div>
		<div class="col-sm-3">
			<label>&nbsp;</label>
			<button type="submit" class="btn btn_primary">Filtrar</button>
		</div>	
	</form>
	<div class="clearfix"></div>
	<div class="col-sm-12 media-tables">
		<table class="media-table">
		  <tr>
		    <th>Título</th>
		    <th>Publicado el</th>
		    <th>Categorías</th>
		  </tr>
		  <?php
                foreach ( $app->get_posts() as $key => $post) { ?>
                	<tr id="delitem<?php echo $post->ID; ?>">
	                	<td>
	                		<a href="<?php echo SITE.'/post/'.$post->ID; ?>" class="media-table-title">
	                			<?php echo $post->post_title; ?>
	                		</a>
							<div class="media-table-actions">
								<a href="<?php echo SITE.'/post/'.$post->ID; ?>" class="media-table-edit-item">
									<i class="fa fa-pencil" aria-hidden="true"></i> Editar
								</a>
								<a href="#" class="media-table-delete-item" data-todelete="<?php echo $post->ID; ?>:ID,posts,delitem<?php echo $post->ID; ?>,¿Deseas eliminar esta Noticia?">
									<i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar
								</a>
							</div>
	                	</td>
						<td><?php $app->prettyDate($post->post_date); ?></td>
						<td><?php $app->category_names($post->post_category); ?></td>
                	</tr>
                <?php 
            	}
		  ?>
		</table>
	</div>
</div>
