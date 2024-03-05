require([
    "jquery"
], function ($) {
	function callback() {
        //window sizes
        var xsmall= 479,
            small= 599,
            medium= 1024,
            large= 1050,
            xlarge= 1199;

        var windowSize = $(window).width();

        if(windowSize > small){
            addScrollableToolbar(true);
            addScrollableFilter('medium');
        } else {
            addScrollableToolbar(0);
            addScrollableFilter('small');
        }
        if(windowSize > medium){
            addScrollableFilter('large');
        }
        
    }
	callback();
    $(window).load(callback());
    $(window).resize(callback());
    // for scrollable toolbar
    function addScrollableToolbar(position){
        var cataloglisting = $('.cataloglisting');
        var block = $('.toolbar');
        if(position === true){
            var fromTop = $('.page-header').height();
        } else{
            var fromTop = 0;
        }
        var blockWidth = $('#amasty-shopby-product-list').width();
        var stickyTop = $('.columns').offset().top; // returns number 
        block.width(blockWidth);
	
        $(window).scroll(function(){ // scroll event  
            var windowTop = $(window).scrollTop()+ fromTop; // returns number

            if (stickyTop < windowTop) {
                block.css({ position: 'fixed', top: fromTop });
                cataloglisting.css({ display: 'none' });
            }
            else {
                block.css('position','static');
                cataloglisting.css({ display: 'block' });
            }
        });
    }
    //For scrolling filter
    function addScrollableFilter(size){
        var filter = $('.sidebar.sidebar-main').first();
        if ( size == 'small' ) {
            var fromTop = 0;
            var delta = 50;
            var filterWidth = $('.sidebar.sidebar-main').width();
            filter.width(filterWidth);
        } else if ( size == 'medium' ) {
            var fromTop = $('.page-header').height();
            var delta = 150;
            var filterWidth = $('.sidebar.sidebar-main').width();
            if($("body").hasClass("account")){filterWidth=230}
            filter.width(filterWidth);
        } else {
            var fromTop = $('.page-header').height();
            var delta = 0;
        }

        var stickyTop = $('.main').offset().top; // returns number

        $(window).scroll(
            function(){ // scroll event
            $("footer").css({'z-index':9999});
            var windowTop = $(window).scrollTop()+ fromTop; // returns number

            if (stickyTop < windowTop) {
            	var totop = fromTop + delta;
                filter.css({ position: 'fixed','top': totop});
            }
            else {
                if(size == 'small') {
                	var top = stickyTop - windowTop;
                	top = top + delta;
                    filter.css({ position: 'fixed','top': top });
                } else {
                    filter.css( 'position', 'static' );
                }
            }
        }
        );
    }
    
    
    /**
	 * Function gets Data Set
	 */
	$(".pdfdownload").click(function () {
		var AjaxUrl = $(".pdfurls").attr('data-href');
		var param = 1;
		$.ajax({
            showLoader: true,
            url: AjaxUrl,
            data: param,
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: "POST"
        }).done(function (outputData) {
            $('.loading-mask').hide();
            $('<form></form>').attr('id', 'downloadFileForm' ).attr('action', BASE_URL + 'customization/index/download').attr('method', 'post' ).appendTo('body');
            $('#downloadFileForm').append('<input type="hidden" name="header_content_description" value="' + outputData.header_content_description + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="header_content_type" value="' + outputData.header_content_type + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="file_name" value="' + outputData.file_name + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="file_path" value="' + outputData.file_path + '"></input');
            $('#downloadFileForm').submit();
            $('#downloadFileForm').remove();
            return false;
        });
	});
	$(".exceldownload").click(function () {
		var AjaxUrl = $(".excelurls").attr('data-href');
		var param = 1;
		$.ajax({
            showLoader: true,
            url: AjaxUrl,
            data: param,
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: "POST"
        }).done(function (outputData) {
            $('.loading-mask').hide();
            $('<form></form>').attr('id', 'downloadFileForm' ).attr('action', BASE_URL + 'customization/index/download').attr('method', 'post' ).appendTo('body');
            $('#downloadFileForm').append('<input type="hidden" name="header_content_description" value="' + outputData.header_content_description + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="header_content_type" value="' + outputData.header_content_type + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="file_name" value="' + outputData.file_name + '"></input');
            $('#downloadFileForm').append('<input type="hidden" name="file_path" value="' + outputData.file_path + '"></input');
            $('#downloadFileForm').submit();
            $('#downloadFileForm').remove();
            return false;
        });
	});

    jQuery(document).on('click', '.rating_dialog',  function(event) {
        var elem = jQuery( event.target );
        var id = elem.attr('data-dialog');
        var url = elem.attr('data-href');
        runDialog(elem,id,url);
    });

    function runDialog(elem,id,url)
    {    
        jQuery( "#dialog-form" ).remove();            
        $.ajax({
            showLoader: true,
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                product_id: id
            },
            success: function (response) {
                $('.loading-mask').hide();
                jQuery('.products-list').first().after(function() {
                    return response.html;
                });

                jQuery( "#dialog-form" ).dialog({
                    autoOpen: false,
                    resizable:true,
                    modal: false,
                    closeOnEscape: false,
                    position: { my: "right+25 top", at: "right+20 bottom", of: elem,collision: "none"},
                });

                jQuery('.ui-dialog-titlebar').first().before(function() {
                    //return '<div class="ratecall">&nbsp;</div>';                      
                });
                jQuery('.ui-button-text').remove();
                jQuery( "#dialog-form" ).dialog( "open" );
                jQuery("#dialog-form").parent().addClass('retting_dialog');
            },
            error: function (xhr, status, errorThrown) {
                console.log('Error happens. Try again.');
            }
        });            
    }
	
	$('#show_score').click(function(){
	  $("#rateform").toggle();
	});
	$('#close_rate').click(function(){
	  $("#rateform").css('display','none');
	});
	
	
	/**
	* Added on 15-01-2020
	*/
	$('#buy_wine').mouseover(function(){
	 	 jQuery('.custom-mega-menu').css('display','none');
		 jQuery('#buy_wine_subheader').css('display','block');
	});
	$('.nav-main').mouseleave(function(){
	 	 jQuery('.custom-mega-menu').css('display','none'); 
		   
	});

    /**
    * Added on 16-01-2020
    */
    $(document).on('click','.customLink', function() {
		if($(window).width() > 1024){
			$("#header_account_sub").toggle();
		}else{
			$(".customLink").toggleClass('parent-menu-active').siblings().removeClass('parent-menu-active');
			$(".mobile-account-details").toggleClass('menu-active').siblings().removeClass('menu-active');
		}
        
    });
	
	
	$(".mobile-menu").click(function(){
	  $(".mobile-menu").toggleClass('parent-menu-active').siblings().removeClass('parent-menu-active');
	  $(".menu-links").toggleClass('menu-active').siblings().removeClass('menu-active');
	});
	$(".mobile-search").click(function(){
	  $(".mobile-search").toggleClass('parent-menu-active').siblings().removeClass('parent-menu-active');
	  $(".mobile-search-details").toggleClass('menu-active').siblings().removeClass('menu-active');
	});
	
    $(document).on('click','#animate_menu_pricelist', function() {
        $("#sidecontent-pricelist").toggleClass('active');
    });
	
	$(document).on('click','.cart-qty', function() {
		$('.action.update').click();
	});
    
});