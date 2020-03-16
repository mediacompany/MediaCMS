<style type="text/css">
.lf_header {
    position: relative;
}
.lf_header_left {
    width: 30%;
    display: inline-block;
}

.lf_header_right {
    width: 67%;
    display: inline-block;
    text-align: right;
}
.lf_button {
    position: relative;
    height: 40px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(236, 239, 241);
    border-radius: 4px;
    padding: 10px;
    -webkit-appearance: none;
    background: #fff;
}
.lf_button:hover {
     box-shadow: rgba(57, 90, 100, 0.1) 0px 2px 9px 0px;
}
.lf_folders{
  border-bottom: 1px solid rgb(236, 239, 241);
  display: block;
  font-size: 0;
  padding-bottom: 20px;
}
.lf_folder {
    display: inline-block;
    align-items: center;
    height: 40px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(236, 239, 241);
    border-radius: 4px;
    margin: 5px;
    width: calc(33.33% - 15px);
}
.lf_folder:hover{
    box-shadow: rgba(57, 90, 100, 0.1) 0px 2px 9px 0px;
}
.lf_folder_icon {
    width: 25px;
    line-height: 40px;
    font-size: 14px;
    text-align: center;
}
.lf_folder_name {
    width: calc(100% - 25px);
    display: inline-block;
    font-size: 15px;
}

.lf_files{
  display: block;
  font-size: 0;
}
.lf_file {
    display: inline-block;
    align-items: center;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(236, 239, 241);
    border-radius: 4px;
    margin: 5px;
    width: calc(33.33% - 15px);
    transition: all .3s;
}
.lf_file:hover{
    box-shadow: rgba(57, 90, 100, 0.3) 0px 2px 9px 0px;
}
.lf_file_selected{
  background-color: #d4d9dd;
  box-shadow: rgba(57, 90, 100, 0.6) 0px 2px 9px 0px !important;
}
.lf_file_preview {
    padding-top: 50%;
    background-color: #ccc;
    background-size: cover;
    background-position: center;
}
.lf_file_icon {
    width: 25px;
    line-height: 40px;
    font-size: 14px;
    text-align: center;
}
.lf_file_name {
    width: calc(100% - 25px);
    display: inline-block;
    font-size: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom:-5px;
}
.lf_button.finish_selection.lf_file_selected {
    background: #d4d9dd;
}

/*UP*/
.dragdrop {
    height: 100%;
    width: 100%;
    margin: 0 auto;
    display: none;
}
.dragdrop.drag-on .upload-area{
    background: #ccc;
}
.upload-area{
    width: 70%;
    height: 300px;
    border: 2px solid lightgray;
    border-radius: 3px;
    margin: 0 auto;
    margin-top: 100px;
    text-align: center;
    overflow: auto;
}

.upload-area:hover{
    cursor: pointer;
}

.upload-area h1 {
    text-align: center;
    font-weight: normal;
    font-family: sans-serif;
    line-height: 45px;
    color: darkslategray;
    margin: 0;
    padding: 65px 0 0 0;
}

#file_input{
    display: none;
}

  </style>
<div class="modal" id="media">
  <div class="modal-overlay"></div>
  <div class="modal-content">
    <a href="#" class="modal-close">X</a>
    <div class="lf_header">
      <div class="lf_header_left">
        <h2 class="lf_title">Archivos</h2>
      </div>
      <input type="file" name="file_input" id="file_input" accept="image/x-png,image/gif,image/jpeg">
      <div class="lf_header_right">
          <button type="button" class="lf_button upload_file"><i class="fa fa-cloud-upload" aria-hidden="true"></i> <span class="toggle_upload_text">Subir archivo</span></button>
          <!-- <button type="button" class="hidden lf_button"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Crear carpeta</button> -->
      </div>
    </div>
    <hr>

    
    <div id="lf_holder">
        <?php
/*function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
*/
          //$file = "/Applications/MAMP/htdocs";
        global $path_base,$url_base;
        //var_dump($path_base);
          $file = '../img/portfolio'; 
          //$a = scandir($dir,1);
          //print_r($a);
          if (is_dir($file)) {
              $directory = $file;
              $result = [];
              //$files = array_diff(scandir($directory), ['.','..']);
              $files = array_diff(preg_grep('/^([^.])/', scan_dir($directory)), ['.','..']);
              foreach ($files as $entry) if (!is_entry_ignored($entry, $allow_show_folders, $hidden_extensions)) {
              $i = $directory . '/' . $entry;
              $stat = stat($i);
                    $result[] = [
                                'mtime' => $stat['mtime'],
                                'mtype' => mime_content_type($i),
                                'size' => $stat['size'],
                                'name' => basename($i),
                                //'path' => preg_replace('@^\./@', '', $i),
                                'path' => $entry,
                                'url' => $url_base.basename($i),
                                'urlenc' => urlencode($i),
                                'is_dir' => is_dir($i),
                                'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||(is_dir($i) && is_writable($directory) && is_recursively_deleteable($i))),
                                'is_readable' => is_readable($i),
                                'is_writable' => is_writable($i),
                                'is_executable' => is_executable($i),
                    ];
                }
            } else {
              echo ("Not a Directory");
            }
            
        ?>
      <h4 class="lf_title">Archivos</h4>
      <div class="lf_files">
        <?php
          foreach ($result as $key => $lf_item) {
                if (!$lf_item['is_dir']) {
                    $mime_images = array('image/png','image/jpeg','image/jpeg','image/jpeg','image/gif','image/bmp','image/vnd.microsoft.icon','image/tiff','image/tiff','image/svg+xml','svgz' => 'image/svg+xml');
                    if(in_array($lf_item['mtype'],$mime_images)) {
                      echo '
                            <div class="lf_file" data-path="'.$lf_item['path'].'" data-fileurl="'.$lf_item['url'].'">
                                <div class="lf_file_preview file_type" style="background-image: url('.$lf_item['url'].');"></div>
                                <div class="lf_file_name_holder">
                                  <i class="lf_file_icon fa fa-file-o" aria-hidden="true"></i><span class="lf_file_name">'.$lf_item['name'].'</span>
                                </div>
                            </div>';
                    }else{
                      echo '
                        <div class="lf_file" data-path="'.$lf_item['path'].'" data-fileurl="'.$lf_item['url'].'">
                            <div class="lf_file_name_holder">
                              <i class="lf_file_icon fa fa-file-o" aria-hidden="true"></i><span class="lf_file_name">'.$lf_item['name'].'</span>
                            </div>
                        </div>';
                    }
                }
            }
        ?>
    </div>
  </div>
  <div class="lf_header">
      <div class="lf_header_left">
        <h2 class="lf_title"></h2>
      </div>
      <div class="lf_header_right">
          <button type="button" class="lf_button finish_selection"><i class="fa fa-check" aria-hidden="true"></i> Usar este archivo</button>
      </div>
  </div>
</div>
</div>
<script>
  // jQuery Plugins
(function ( $ ) {
  //var norepeat = 0;
  $.fn.lightfile = function(ajaxurl = "", param = 'open', target = null) {
    console.log(target)
      //this.css( "color", "green" );
      if (param == 'open') {
        this.fadeIn().addClass('modal_open')
      }else{
        return false;
        this.fadeOut().removeClass('modal_open')
      }
      $('.modal-overlay').click(function(){
        $('.modal').fadeOut().removeClass('modal_open')
      })
      $('.modal-close').click(function(e){
        e.preventDefault()
        $(this).closest('.modal').fadeOut().removeClass('modal_open')
      })
      
      // modify to reload on upload
      $(document).on('click','.route_handler', function(e){
          e.preventDefault()
          $('#folder').val($(this).attr('href'))
          $.get(ajaxurl+"?do=list", function(data, status){
              $('#lf_holder').hide().html(data).fadeIn()
          });
      })
      $(document).on('click','.lf_file', function(e){
        e.preventDefault()
        $('.lf_file').removeClass('lf_file_selected')
        $(this).addClass('lf_file_selected')
        $('.finish_selection').addClass('lf_file_selected').attr('data-url',$(this).data('fileurl'))
      })
      function reloadFiles(){
        $.get(ajaxurl+"?do=list", function(data, status){
            $('#lf_holder').hide().html(data).fadeIn()
        });
      }

      // Open file selector on div click
      $(".upload_file").click(function(){
          $("#file_input").click();
      });

      // file selected
      $("#file_input").change(function(){
          if ($("#file_input").val() != '') {
            var fd = new FormData();
            var files = $('#file_input')[0].files[0];
            fd.append('do','upload');
            fd.append('upload_file',files);
            uploadData(fd);
          } 
      })

      // Sending AJAX request and upload file
      function uploadData(formdata){
        //console.log('executed uploadData')
        //console.log(formdata)
        //console.log(ajaxurl)
         $.ajax({
              url: ajaxurl,
              type: 'POST',
              data: formdata,
              contentType: false,
              processData: false,
              //dataType: 'json',
              success: function(response){
                if (response) {
                  reloadFiles()
                  $("#file_input").val('');
                }else{
                  alert('Algo salio mal 😢')
                }
                //console.log(response)
                  //addThumbnail(response);
              }
          });
      }

      /////// DRAG DROP END

      this.find('.finish_selection').click(function(){
        var $value = $(this).attr('data-url');
        for(var i in target) {
          //console.log(target[i]);

          if (i == 'bg') {
            $(target[i]).hide().css('background-image','url('+$value+')').fadeIn()

          }
          if (i == 'val') {
            //console.log(target[i])
            var arraySlug = $value.split('/');
            var slug = arraySlug[arraySlug.length-1];
            console.log("Nueva imagen detectada, su slug: " + slug);     
            $(target[i]).val(slug);
          }
          if (i == 'event') {
              $( document ).trigger( target[i], [$value] );
          }
          $(this).attr('data-url','').removeClass('lf_file_selected')
          $('.lf_file').removeClass('lf_file_selected')
        }
        //console.log(norepeat)
        //norepeat = 0;
        //console.log(norepeat)
        target = null;
        $(this).closest('.modal').fadeOut().removeClass('modal_open')
        
      })
      return this;
  };
}( jQuery ));

jQuery(document).ready(function($){
  var urlfile = 'http://localhost:8080/public_html/admin/file-functions.php';
  //var urlfile = 'https://www.biglieri.com.ar/admin/file-functions.php';
  $('.post_image').click(function(e){
      $('#media').lightfile(urlfile,'open', {'bg':'.post_image','val':'#post_image_hidden'});
  })
  $('.post_image_small').click(function(e){
      $('#media').lightfile(urlfile,'open', {'bg':'.post_image_small','val':'#post_image_small_hidden'});
  })
})
</script>
