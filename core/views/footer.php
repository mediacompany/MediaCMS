</div>
<div class="modal" id="modal">
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<a href="#" class="modal-close"><i class="fa fa-times" aria-hidden="true"></i></a>
		<div class="info_dinamyc">a</div>
	</div>
</div>

<div class="modal" id="infos">
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<a href="#" class="modal-close"><i class="fa fa-times" aria-hidden="true"></i></a>
		<div class="info_dinamyc"></div>
	</div>
</div>
<div class="modal" id="deletes">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="delete_checkmark">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
        </div>
        <div class="info_dinamyc">
        </div>
        <div class="row mt20">
            <div class="col-sm-6">
                <button type="button" class="btn btn_secondary modal-close delete_cancel">Cancelar</button>
            </div>
            <div class="col-sm-6">
                <form class="ajax_form">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="sqlcondval" id="sqlcondval" value="">
                    <input type="hidden" name="sqltable" id="sqltable" value="">
                    <input type="hidden" name="domresp" id="domresp" value="">
                    <button type="submit" class="btn btn_primary delete_confirm">Aceptar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITE; ?>/core/assets/js/jquery.min.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/bootstrap.min.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/plugins.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/datepicker.min.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/datepicker.es.min.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/multiple-select.js"></script>
<script src="<?php echo SITE; ?>/core/assets/js/main.js"></script>
<?php $core->footer(); ?>

<?php //include('media_manager.php'); ?>
<script type="text/javascript">
    /*
    $('#post_text').trumbowyg({
        btns: [
        	['bold', 'italic'],['h1', 'h2'],['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],['unorderedList', 'orderedList'], ['link'],['addAttachment'],['outdent','indent']
        ],
        removeformatPasted: true,
        autogrow: true,
        tagsToRemove: ['script'],
        btnsDef: {
            addAttachment: {
                fn: function() {
                        var urlfile = 'http://localhost:8888/Biglieri/admin/file-functions.php';
                        var mediafiles = $('#media').lightfile(urlfile,'open', {'event':'editor_media_selected'});
                        var cero = 0;
                        $( document ).on( "editor_media_selected", function( event, arg ) {
                                //console.log( arg );
                                console.log('event editor')
                                $('#post_text').trumbowyg('restoreRange');
                                if (arg != '' && cero === 0) {
                                $('#post_text').trumbowyg('execCmd', {
                                    cmd: 'insertHtml',
                                    param: '<img src="'+arg+'" style="width: 100%;height: auto;max-width: 80%;margin: 15px auto;display: block;">'
                                });
                                }
                                $('#post_text').trumbowyg('saveRange');
                                cero++;
                        });
                        mediafiles = null;
                        cero = 0;
	            },
                title: 'Insertar imagen',
                isSupported: function () { return true; },
                hasIcon: true,
                ico: 'insert-image'
            }
        }
    });

    $('.trumbowyg-editor').on('keydown keypress', function(event){
        if (event.keyCode === 9) { // tab key
            event.preventDefault(); // prevent the tab key event from bubbling up
            if(event.shiftKey){
                $('#post_text').trumbowyg('execCmd', {
                    cmd: 'outdent',
                    param: null,
                    forceCss: false,
                });
            }
            else{
                $('#post_text').trumbowyg('execCmd', {
                    cmd: 'indent',
                    param: null,
                    forceCss: false,
                });
            }
        }
    });
    
*/
    function string_to_slug (str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
      
        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to   = "aaaaeeeeiiiioooouuuunc------";
        for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    }
    jQuery(document).ready(function($){
        $(".make_slug").keyup(function (evento) {
            var $receiber_pickup = $(this).data('pickup');
            $(".make_slug_pickup_"+$receiber_pickup).val(string_to_slug($(this).val()));   
        });
        $.fn.wrapInTag = function(opts) {

          var tag = opts.tag || 'strong'
            , words = opts.words || []
            , regex = RegExp(words.join('|'), 'gi') // case insensitive
            , replacement = '<'+ tag +'>$&</'+ tag +'>';

          return this.html(function() {
            return $(this).text().replace(regex, replacement);
          });
        };
        var tags = $('#post_metatags').val();
        $('.urlVerde span').text($('#post_slug').val())
        $('.tituloAzul').text($('#post_title').val())
        if ($('.textoGris').length) {
            $('.textoGris').wrapInTag({
                      tag: 'b',
                      words: tags.split(',')
            });
        }
        
        //$('#linkprev').attr('href','https://mediahaus.com.ar/nota/'+$('#post_slug').val())
        $('#post_title').keyup(function(e){
            $('.urlVerde span').text($('#post_slug').val())
            $('.tituloAzul').text($(this).val())
            //$('#linkprev').attr('href','https://mediahaus.com.ar/nota/'+$('#post_slug').val())
        });
        $('#post_metadescription').keyup(function(e){
            var seocounter = $(this).data('counter'), cmax = $(this).data('cmax'), text = $(this).val(), tags = $('#post_metatags').val();
            $('.'+seocounter).text(cmax-text.length)
            //console.log(text.length)
                $('.textoGris').text(text)
                $('.textoGris').wrapInTag({
                  tag: 'b',
                  words: tags.split(',')
                });
        });
        $('#post_metadescription').keyup()
        $('.cerrarchat').click(function(){
            $('.chat').hide()
        })
        $('.verchat').click(function(e){
            e.preventDefault();
            $('.chat').hide()
            $('#chat'+$(this).data('chat')).fadeIn()
        })
    })

</script>

</body>
</html>