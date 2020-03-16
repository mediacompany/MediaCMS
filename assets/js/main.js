jQuery(document).ready(function($){
	//toggle sidebar
	$('.nav-sidebar-toggler').click(function(){
		$('body').toggleClass('open-nav-sidebar')
		if ($('body').hasClass('open-nav-contact')) {
			$('body').removeClass('open-nav-contact')
		}
	})
	//toggle sidebar contact
	$('.nav-contact-toggler, #services-text2 a').click(function(){
		$('body').toggleClass('open-nav-contact')
		return false;
	})
	//hide sidebars click out of these
	$('.content-area, .header-main').click(function(event) {
	    if ( !$(event.target).closest( ".nav-sidebar-toggler" ).length ) {
           $('body').removeClass('open-nav-sidebar open-nav-contact')
        }
    });
    //contact form ajax
	$('#nav-form').submit(function(){
		var loader = $('.nav-form-loader'); loader.slideDown(), form = $(this)
		$.post(base_url+'ajax',form.serialize(), function(resp, status){
			loader.fadeOut()
			if(status == 'success') {form.trigger("reset");}
			$('.nav-form-resp').text(resp).slideDown()
			setTimeout(function(){
				$('.nav-form-resp').slideUp().text('')
			}, 5000);
		})
		return false;
	})
    //hover mobile hover activator
	$(document).on('touchstart', function() {
	    documentClick = true;
	});
	$(document).on('touchmove', function() {
	    documentClick = false;
	});
	$(document).on('click touchend', function(event) {
	    if (event.type == "click") documentClick = true;
	    if (!documentClick){
	        //doStuff();
	    	return false;
	    }
	 });
	//filtrable grid activator
	if ($('.is-filterable-grid')) {
	    let $grid = $('.is-filterable-grid');
	    let $items = $grid.children('.card');
	    let currentCat = 'all';
	    let sidebar = [];
	    let sortItems = (a,b) => {
	      let an = a.getAttribute('data-order');
	      let bn = b.getAttribute('data-order');
	      if(an > bn) { return 1; }
	      if(an < bn) { return -1; }
	      return 0;
	    }
	    let filterItems = function(event) {
	      let cat = this.getAttribute('data-category');
	      let newSidebar = [];
	      $('.button--is-active').toggleClass('button--is-active');
	      $(`.button[data-category=${cat}]`).toggleClass('button--is-active');
	      if($(event.target).attr('class').split(' ').join('') === 'reset-filtericon-x') {
	            cat = 'all';
	            $('.js-button-filter').removeClass('button--is-active');
	      }
	      $grid.fadeOut( "1", function() {
	        sidebar.map((item) => $(item).appendTo($grid));
	        
	        //console.log($(event.target).attr('class').split(' ').join(''))
	        if(cat === 'all') {
	          $items.sort(sortItems).detach().appendTo($grid);
	        }else {
	          $(`.card:not([data-category=${cat}])`).each(function() {
	            newSidebar.push($(this).detach());
	          });
	        }
	        //if(){
	        //     $items.sort(sortItems).detach().appendTo($grid);
	        //}
	        sidebar = newSidebar;
	        currentCat = cat;
	      }).fadeIn("50");
	      console.log(cat)
	      return false;
	    };
	    // Handle the click on a category filter button
	    $('.js-button-filter').click(filterItems);
	   /* $('.filter-menu-select').change(function(){
	      var cat = $(this).val()
	      $(`.button[data-category=${cat}]`).trigger('click');
	    })*/
/*$('.reset-filter').click(function(event){
	        console.log($(this))
	        console.log($(event.target))
	        if ( $(event.target) == $(this) ) {
	            console.log('x')
                $(`.button[data-category='all']`).trigger('click');
            }
	    })*/
	}
	//resize
	$(window).on('resize', function(){
      var win = $(this); //this = window
          //if (win.height() >= 820) { /* ... */ }
        //  if (win.width() <= 1024) {  $("video").prop("controls",true);$("video").attr("controls",true);  }else{$("video").prop("controls",false);$("video").attr("controls",false);}
    });
	//load class trigger class
	$(window).on('load', function(){
	    $('body').addClass('site-loaded');
	    console.log('With ❤ by MediaCompany Dev\'s️')
	});
})