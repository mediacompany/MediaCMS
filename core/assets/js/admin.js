 /* Archivo generado automaticamente por MediaCMS */ 
 /* Todos los derechos reservados por MediaHaus by MediaCo. */ 
 /* Nombre del archivo: admin.js */
 jQuery(document).ready(function($){
	$('.sameh_trigger').sameheight()
	$('.media-table-delete-item').click(function(e){
		e.preventDefault()
		var params = $(this).data('todelete').split(',');
		if (params[3]) {
			$('#deletes').modal().find('.info_dinamyc').text(params[3])
		}else{
			$('#deletes').modal().find('.info_dinamyc').text('¿Deseas eliminar este elemento?')
		}
		$('#sqlcondval').val(params[0])
		$('#sqltable').val(params[1])
		$('#domresp').val(params[2])
	})
	function ajax_resp_actions(action,param,state){
		switch (action){
			case 'reload':
					location.reload()
				break;
			case 'show-error':
					$('#infos').modal().find('.info_dinamyc').text(param)
				break;
			case 'delete_item':
					$('.delete_checkmark .checkmark').show()
					setTimeout(function(){
						$('#deletes').modal('close')
						$('#'+param).fadeOut(function(){
							$('.delete_checkmark .checkmark').hide()
							$(this).remove();
						})

					}, 1000);
					
				break;
			default:
				alert('error on action')
		}
	}
	$('.ajax_form').submit(function(){
		$.post(base_url+'admin/ajax',$(this).serializeArray(), function(data){
			console.log(data)
			ajax_resp_actions(data.action,data.param,data.state)
		})
		return false;
	})
	$('#toggler_sider').click(function(){
		$('#toggler_sider, body').toggleClass('sideClose')
		var sidebar = getCookie("sidebar");
		if (sidebar == 0) {
		    setCookie("sidebar", 1, 1);
		}else{
			setCookie("sidebar", 0, 1);
		}
	})
	$('.color_picker input, .color_picker .color_picker_preview').click(function(){
		$(this).parent().addClass('color_picker_open')
		$(this).closest('.color_picker').find('.color_picker_selector').slideDown()
	})
	$('.color-block').click(function(){
		$('.color_picker').removeClass('color_picker_open')
		$(this).closest('.color_picker').find('.color_picker_selector').slideUp()
		$(this).closest('.color_picker').find('.color_picker_val').val($(this).data('hex'))
		$(this).closest('.color_picker').find('.color_picker_preview').css('background',$(this).data('hex'))
	})
	$('#act').click(function(){
		$('#modal').modal();
	})

	$('.open_modal').click(function(e){
		e.preventDefault()
		$($(this).attr('href')).modal();
	})
	$('#task_area').change(function(){
		$.post(base_url+'ajax',{'action':'get_resources_onchange','filter': $(this).val()},function(resp){
			$('#ID_resources').hide().html(resp).delay(200).fadeIn()
		})
	})
	$('.tab_header').tabs()
	$('#client_resources').multipleSelect(
		{
		      filter: true,
		      formatSelectAll () {
		        return 'Seleccionar Todos'
		      },
		      formatAllSelected () {
		        return 'Todos Seleccionados'
		      }
		      /*,
		      formatCountSelected (count, total) {
		        return '已从 ' + total + ' 中选择 ' + count + ' 条记录'
		      },
		      formatNoMatchesFound () {
		        return '没有找到记录'
		      }*/
		    }
	)
	$('.countdown').countdown()

	function formatMoney(number, decPlaces, decSep, thouSep) {
	    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
	    decSep = typeof decSep === "undefined" ? "." : decSep;
	    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
	    var sign = number < 0 ? "-" : "";
	    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
	    var j = (j = i.length) > 3 ? j % 3 : 0;

	    return sign +
	        (j ? i.substr(0, j) + thouSep : "") +
	        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
	        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
	}

	$('#task_price_fake').change(function(e){
		var $this = $(this), $val = $this.val(), $orig = $('#task_price'), $tax = $('#task_price_tax');
		$orig.val($val)
		$tax.val($val*0.21)
		$this.val(formatMoney($val))
		$('.task_price_tax_span').text(formatMoney($val*0.21))
	})
	$('input[name="task_payment"]').change(function(e){
		var $this = $(this), $val = $this.val(), $tax = $('#task_price_tax'), $price = $('#task_price').val();
		if ($val == 1) {
			$('.task_price_tax').css('opacity',1)
		}else{
			$('.task_price_tax').css('opacity',0)
		}
		$tax.val($price)
		$('.task_price_tax_span').text(formatMoney($price*0.21))
	})
})
