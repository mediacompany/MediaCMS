<?php
global $core;
$textos = $core->get_all('pages');
$post = $core->request()->data;
if (!empty($post->ID)) {
	$multimedia = null;
	if (!empty($post->media_type)) {
		foreach ($post->media_type as $key => $value) {
			$multimedia['media_'.$key] = array('type' => $value, 'value' => $post->media_name[$key]);
		}
	}
	$extra_data_serialized = serialize(array(
		'project_url' => $post->project_url,
		'client' => $post->client,
		'project_date' => $post->project_date,
		'multimedia' => $multimedia,
		'servicios' => implode(',', $post->servicio)
	));
	$core->update_on('portfolio',array(
		'category' => $post->category,
		'title' => $post->title,
		'slug' => $post->slug,
		'description_es' => htmlspecialchars($post->description_es,ENT_QUOTES),
		'description_en' => htmlspecialchars($post->description_en,ENT_QUOTES),
		'description_pt' => htmlspecialchars($post->description_pt,ENT_QUOTES),
		'portfolio_order' => $post->portfolio_order,
		'portfolio_visibility' => $post->portfolio_visibility,
		'extra_data' => $extra_data_serialized
		),array('ID' => $post->ID)
	);
	echo "ok";
	die;
}
if (!empty($id)) {
	$proyecto = $core->get_row('portfolio','*', 'WHERE ID = '.$id);
	$extra_data = unserialize($proyecto->extra_data);
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title>Editor Temporal</title>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}
	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}
	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	textarea {
	    width: 100%;
	    min-height: 50px;
	    height: auto;
	    overflow: hidden;
	}
	input, select {
	    width: 100%;
	    min-height: 35px;
	}
	input[type="checkbox"] {
	    width: 20px;
	    vertical-align: top;
	}
	img.loader {
	    display: none;
	    width: 40px;
	}
	form.form-autosave {
	    position: relative;
	}
	label {
	    cursor: pointer;
	}
	button{
		padding: 12px 30px;
		cursor: pointer;
		margin:15px 0;
	}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
	<select id="proyectos">
		<option>Seleccionar</option>
		<?php 
			foreach ($core->get_all('portfolio') as $key => $value) {
				echo "<option value='{$value->ID}'>{$value->ID} - {$value->title}</option>";
			}
		?>
	</select>
	<?php if (!empty($id)): ?>
	<h1><?php echo "Editando $id"; ?> - <?php echo $proyecto->title; ?> </h1>
	<form class="form-autosave">
		<input type="hidden" name="ID" value="<?php echo $proyecto->ID; ?>">
		<button type="submit">Guardar</button>
	<table>
				<tbody>
					<tr>
						<td style="width: 300px">Categoria</td>
						<td>
							<select id="categoria" name="category">
								<?php 
									foreach ($core->get_all('portfolio_categories') as $key => $value) {
										if ($proyecto->category == $value->ID) {
											echo "<option value='{$value->ID}' selected>{$value->title_es}</option>";
										}else{
											echo "<option value='{$value->ID}'>{$value->title_es}</option>";
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Título</td>
						<td><input type="text" name="title" value="<?php echo $proyecto->title; ?>"></td>
					</tr>
					<tr>
						<td>Slug(url)</td>
						<td><input type="text" name="slug" value="<?php echo $proyecto->slug; ?>"></td>
					</tr>
					<tr>
						<td>Descripción ES</td>
						<td><textarea name="description_es" class="" onkeyup="textAreaAdjust(this)"><?php echo htmlspecialchars_decode($proyecto->description_es); ?></textarea></td>
					</tr>
					<tr>
						<td>Descripción EN</td>
						<td><textarea name="description_en" class="" onkeyup="textAreaAdjust(this)"><?php echo htmlspecialchars_decode($proyecto->description_en); ?></textarea></td>
					</tr>
					<tr>
						<td>Descripción PT</td>
						<td><textarea name="description_pt" class="" onkeyup="textAreaAdjust(this)"><?php echo htmlspecialchars_decode($proyecto->description_pt); ?></textarea></td>
					</tr>
					<tr>
						<td>Servicios</td>
						<td>
							<?php
								$servicios = array(1 => 'CONSULTORÍA', 2 => 'CONTENIDO MULTIMEDIA', 3 => 'DESARROLLO WEB', 4 => 'RENDERIZACIÓN CGI', 5 => 'APPS', 6 => 'REALIDAD VIRTUAL', 7 => 'REDACCIÓN CREATIVA', 8 => 'DISEÑO GRÁFICO', 9 => 'REDES SOCIALES', 10 => 'IDENTIDAD', 11 => 'POSICIONAMIENTO DIGITAL', 
								12 => 'MAILING', 13 => 'COMUNICACIONES INTERNAS'); 
								foreach ($servicios as $key => $value) {
									if (in_array($key, explode(',', $extra_data['servicios'] ) ) ) {
										echo "<label><input type='checkbox' name='servicio[]' checked value='{$key}'>{$value}</label>";
									}else{
										echo "<label><input type='checkbox' name='servicio[]' value='{$key}'>{$value}</label>";
									}
									
								}
							?>
						</td>
					</tr>
					<tr>
						<td>URL</td>
						<td><input type="text" name="project_url" value="<?php echo $extra_data['project_url']; ?>"></td>
					</tr>
					<tr>
						<td>Cliente</td>
						<td><input type="text" name="client" value="<?php echo $extra_data['client']; ?>"></td>
					</tr>
					<tr>
						<td>Fecha</td>
						<td><input type="date" name="project_date" value="<?php echo $extra_data['project_date']; ?>"></td>
					</tr>
					<tr>
						<td>Multimedia</td>
						<td>
							<div class="multimedia-project">
								<button type="button" id="agregar_medio">Agregar</button>
								<div class="dinamico">
									<?php
										if (!empty($extra_data['multimedia'])) {
											foreach ($extra_data['multimedia'] as $key => $multimedia) {
												$selected_imagen = ($multimedia['type'] == 'imagen')? 'selected' : '' ;
												$selected_video = ($multimedia['type'] == 'video')? 'selected' : '' ;
												echo '<div class="medio-elm" id="'.$key.'">
														<select name="media_type[]">
															<option value="imagen" '.$selected_imagen.'>imagen</option>
															<option value="video" '.$selected_video.'>video</option>
														</select>
														<input type="text" name="media_name[]" value="'.$multimedia['value'].'" placeholder="Nombre del elemento multimedia">
														<button type="button" class="delete_this">Eliminar</button>
													</div>';
											}
										}
									?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>Orden</td>
						<td><input type="number" name="portfolio_order" value="<?php echo $proyecto->portfolio_order; ?>" min="0"></td>
					</tr>
					<tr>
						<td>Visible</td>
						<td>
							<select name="portfolio_visibility">
								<option value="1" <?php echo ($proyecto->portfolio_visibility == 1)? 'selected' : '' ; ?>>SI</option>
								<option value="0" <?php echo ($proyecto->portfolio_visibility == 0)? 'selected' : '' ; ?>>NO</option>
							</select>
					</tr>
				</tbody>
	</table>
		<img src="<?php echo SITE; ?>assets/img/ajax-loader.gif" class="loader">
		<button type="submit">Guardar</button>
	</form>
	<?php 
endif;
	?>
	<script type="text/javascript">
		function textAreaAdjust(o) {
		  o.style.height = "1px";
		  o.style.height = (25+o.scrollHeight)+"px";
		}
		$('.form-autosave').submit(function(){
			var loader = $('.loader')
			loader.show()
			$.post('<?php echo SITE; ?>editar-proyecto/', $(this).serialize(),function(response){
				console.log(response)
				if (response == 'ok') {
					loader.hide()
				}
			})
			return false;
		});
		$("#proyectos").change(function(){
			location.href = "<?php echo SITE; ?>editar-proyecto/"+$(this).val()
		});
		$('#agregar_medio').click(function(){
			var medio_base = '\
			<div class="medio-elm">\
				<select name="media_type[]">\
					<option value="imagen">imagen</option>\
					<option value="video">video</option>\
				</select>\
				<input type="text" name="media_name[]" value="" placeholder="Nombre del elemento multimedia"><button type="button" class="delete_this">Eliminar</button>\
			</div>\
			';
			$('.dinamico').hide().append(medio_base).fadeIn();
		})
		$(document).on('click','.delete_this', function(){
			var $this = $(this).closest('.medio-elm')
			$this.fadeOut(function(){
				$this.remove()
			})
		})
	</script>
</body>
</html>