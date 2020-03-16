<?php
global $core;
$textos = $core->get_all('pages');
$post = $core->request()->data;
if (!empty($post->ID) && !empty($post->text_content)) {
	$core->update_on('pages',array('text_content' => htmlspecialchars($post->text_content,ENT_QUOTES)),array('ID' => $post->ID));
	echo "ok";
	die;
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
	img.loader {
	    display: none;
	    width: 40px;
	    position: absolute;
	    top: 0;
	    right: 0;
	}
	form.form-autosave {
	    position: relative;
	}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
	<h1>Textos Fijos</h1>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>KEY</th>
				<th>VALOR</th>
			</tr>
		</thead>
		<tbody>
			<?php
					foreach ($textos as $key => $value) { ?>
						<tr>
							<td><?php echo $value->ID; ?></td>
							<td><?php echo $value->text_key; ?></td>
							<td>
								<form class="form-autosave">
									<img src="<?php echo SITE; ?>assets/img/ajax-loader.gif" class="loader">
									<input type="hidden" name="ID" value="<?php echo $value->ID; ?>">
									<input type="hidden" name="text_key" value="<?php echo $value->text_key; ?>">
									<textarea name="text_content" class="text_content" onkeyup="textAreaAdjust(this)"><?php echo htmlspecialchars_decode($value->text_content); ?></textarea>
									<button type="submit">Guardar</button>
								</form>
							</td>
						</tr>
			<?php	}
			?>
		</tbody>
	</table>
	<script type="text/javascript">
		function textAreaAdjust(o) {
		  o.style.height = "1px";
		  o.style.height = (25+o.scrollHeight)+"px";
		}
		//
		// $('#element').donetyping(callback[, timeout=1000])
		// Fires callback when a user has finished typing. This is determined by the time elapsed
		// since the last keystroke and timeout parameter or the blur event--whichever comes first.
		//   @callback: function to be called when even triggers
		//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
		//              caused by blur.
		// Requires jQuery 1.7+
		//
		;(function($){
		    $.fn.extend({
		        donetyping: function(callback,timeout){
		            timeout = timeout || 1e3; // 1 second default timeout
		            var timeoutReference,
		                doneTyping = function(el){
		                    if (!timeoutReference) return;
		                    timeoutReference = null;
		                    callback.call(el);
		                };
		            return this.each(function(i,el){
		                var $el = $(el);
		                // Chrome Fix (Use keyup over keypress to detect backspace)
		                // thank you @palerdot
		                $el.is(':input') && $el.on('keyup keypress paste',function(e){
		                    // This catches the backspace button in chrome, but also prevents
		                    // the event from triggering too preemptively. Without this line,
		                    // using tab/shift+tab will make the focused element fire the callback.
		                    if (e.type=='keyup' && e.keyCode!=8) return;
		                    
		                    // Check if timeout has been set. If it has, "reset" the clock and
		                    // start over again.
		                    if (timeoutReference) clearTimeout(timeoutReference);
		                    timeoutReference = setTimeout(function(){
		                        // if we made it here, our timeout has elapsed. Fire the
		                        // callback
		                        doneTyping(el);
		                    }, timeout);
		                }).on('blur',function(){
		                    // If we can, fire the event since we're leaving the field
		                    doneTyping(el);
		                });
		            });
		        }
		    });
		})(jQuery);

		$('.text_content').donetyping(function(){
			//$(this).closest('.form-autosave').submit()
		});
		$('.form-autosave').submit(function(){
			var loader = $(this).find('.loader')
			loader.show()
			$.post('<?php echo SITE; ?>editar', $(this).serialize(),function(response){
				console.log(response)
				if (response == 'ok') {
					loader.hide()
				}
			})
			return false;
		});
	</script>
</body>
</html>