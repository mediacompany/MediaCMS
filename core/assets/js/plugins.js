//adjust  textarea automatic height from content
function textAreaAdjust(o) {
  o.style.height = "1px";
  o.style.height = (o.scrollHeight)+"px";
}
//cookie management
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
// preview Image upload
function showImage(src, target) {
    var fr = new FileReader();
    fr.onload = function(){
        //target.src = fr.result;
        $('#'+target).css('background-image','url('+fr.result+')')
    }
    fr.readAsDataURL(src.files[0]);

}
function putImage(src,target) {
    var src = document.getElementById(src);
    showImage(src, target);
}



// jQuery Plugins
(function ( $ ) {
  // MediaCore Modal
  $.fn.modal = function(param = 'open') {
      //this.css( "color", "green" );
      if (param == 'open') {
        this.fadeIn().addClass('modal_open')
      }else{
        this.fadeOut().removeClass('modal_open')
      }
      $('.modal-overlay').click(function(){
        $('.modal').fadeOut().removeClass('modal_open')
      })
      $('.modal-close').click(function(e){
        e.preventDefault()
        $(this).closest('.modal').fadeOut().removeClass('modal_open')
      })
      return this;
  };
  // MediaCore Tabs
  $.fn.tabs = function(param = '') {
    var $tab_holder = this.data('tab')
    var $tab_content = $('#'+$tab_holder).find('.tab_content')
    var $tab_a = this.find('a')
    var $tab_a1 = this.find('li').first().find('a')
    var url = window.location.href;
    var hash = url.split('#')[1];
    $tab_a.click(function(e){
        $tab_a.removeClass('tab_active')
        $tab_content.hide().removeClass('tab_active')
        $($(this).attr('href')).fadeIn();
        $(this).addClass('tab_active')
    })
    if(hash) {
        if ($('#'+hash).length){
            $tab_a.removeClass('tab_active')
            $tab_content.hide().removeClass('tab_active')
            $('a[href="#'+hash+'"]').addClass('tab_active')
            $('#'+hash).fadeIn();
        }
    }else{
      //tab_active
      if ($tab_a1.length) {
        $tab_a1.addClass('tab_active')
      }
      
    }
    return this;
  };
  // same height
  $.fn.sameheight = function() {
      $(this).each(function(){  
        
        // Cache the highest
        var highestBox = 0;
        
        // Select and loop the elements you want to equalise
        $('.sameh', this).each(function(){
          
          // If this box is higher than the cached highest then store it
          if($(this).height() > highestBox) {
            highestBox = $(this).height(); 
          }
        
        });  
              
        // Set the height of all those children to whichever was highest 
        $('.sameh',this).height(highestBox);
                      
      }); 
  };
  // countdown
  $.fn.countdown = function() {
    $(this).each(function(){
    var $elm = $(this), deadline = $elm.data('deadline'), countDownDate = new Date(deadline).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();
        
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Output the result in an element
      var time = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
      $elm.find('.countdown_dybnamic').html(time);
      if (hours < 2) {
        $elm.addClass('countdown_expire')
      }
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        $elm.addClass('countdown_expire').find('.countdown_dybnamic').html("DELAYED");
      }
    }, 1000);
    }); 
    return this;
  };

  

/*document.getElementById("b").addEventListener("click", event => {
  document.getElementById("x").innerText = "Result was: " + formatMoney(document.getElementById("d").value);
});*/
}( jQuery ));