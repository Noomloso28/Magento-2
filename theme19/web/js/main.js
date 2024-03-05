require([
    "jquery"
], function ($) {
	$(document).on('click', function (e) {
   		var parent = e.target;
   		var clickedcls = $(parent).attr('class');
   		var parentcls = $(parent).parent().attr('class');
   		$($("div.filter-options")[0]).find(".active").each(function () {
            if(clickedcls!='filter-options-title'){
            	if(parentcls!='amshopby-search-box' && parentcls!='range am-fromto-widget amshopby_currency_rate'){
	            	if(clickedcls!='am-show-more' && clickedcls!='am-show-more -active' && clickedcls!='filter-options-content' && clickedcls!='am-filter-price -from input-text' && clickedcls!='am-filter-go' && clickedcls!='am-filter-price -to input-text'){
	            		$(this).removeClass('active');
	            	}
				}
            }
        });
	});
    /**
     * Function gets Data Set
     */
     $(document).on("click",".pdfdownload", function() {
        var AjaxUrl = $(".pdfurls").attr('data-href');
        var formkeyid = $.mage.cookies.get('form_key');
        var param = $(".filterdata").val();
        $.ajax({
            showLoader: true,
            url: AjaxUrl,
            data: {rst:param},
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
            $('#downloadFileForm').append('<input type="hidden" name="form_key" value="' + formkeyid + '"></input');
            $('#downloadFileForm').submit();
            $('#downloadFileForm').remove();
            return false;
        });
    });
    $(document).on("click",".exceldownload", function() {
        var AjaxUrl = $(".excelurls").attr('data-href');
        var formkeyid = $.mage.cookies.get('form_key');
        var param = $(".filterdata").val();
        $.ajax({
            showLoader: true,
            url: AjaxUrl,
            data: {rst:param},
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
            $('#downloadFileForm').append('<input type="hidden" name="form_key" value="' + formkeyid + '"></input');
            $('#downloadFileForm').submit();
            $('#downloadFileForm').remove();
            return false;
        });
    });

    jQuery(document).on('mouseover', '.rating_dialog',  function(event) {
        var elem = jQuery( event.target );
        var id = elem.attr('data-dialog');
        //var url = elem.attr('data-href');
        var htm = jQuery('.ratingdia_html_'+id).html();
        //runDialog(elem,id,url);
        openDialogPoup(elem,htm,id);
    });
     jQuery(document).on('mouseout', '.rating_dialog',  function(event) {
        var elem = jQuery( event.target );
        var id = elem.attr('data-dialog');
        jQuery('.ratingdia_html_'+id).dialog( "close" );
     });

    function openDialogPoup(elem,htmlcont,id){
        //jQuery('.hiderating').hide();
        jQuery('.ratingdia_html_'+id).dialog({
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
        jQuery('.ratingdia_html_'+id).dialog( "open" );
        jQuery('.ratingdia_html_'+id).show().parent().addClass('retting_dialog');
    }

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

    $(document).on('click','#animate-menu-pricelist', function() {
        $("#sidecontent-pricelist").toggleClass('active');
    });

    $(document).on('change input','.cart-qty', function() {
        $('.action.update').click();
    });



	/*
	* Added  on 22-02-2020
	*/
	//Primeur modal dialog
	jQuery(document).on('click', '#togglePrimeurModal',function(e) {
	    if (jQuery('.fullscreen_show').css('display') == 'none') {
	        jQuery('.fullscreen_show').show();
	    }else{
	        jQuery('.fullscreen_show').hide();
	    }
	    var id = jQuery(this).attr('primeur');
	    jQuery('#guest_primeur_email').val('');
	    jQuery('#primeur_product_id').val(id);
	    jQuery('#primeurModal').fadeToggle('fast', 'linear');
	    e.preventDefault();
	});
	//Close modal
	jQuery(document).on('click', '#closeModal',function() {
	    //jQuery('.g-recaptcha-error').html('');
	    jQuery('.fullscreen_show').hide();
	    jQuery('#primeurModal').fadeToggle('fast', 'linear');
	});
	//Close modal
	jQuery(document).on('click', '#closePrimeurSuccessModal',function() {
	   jQuery('.fullscreen_show').hide();
	   jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
	});
	//guest modal
	jQuery(document).on('click', '#send_guest_primeur_email',function() {
		var error = '<p>Dies ist ein Pflichtfeld.</p>';
		var error1 = '<p>Email id not valid.</p>';
	    var email = jQuery('#guest_primeur_email').val().replace(/<|>/g, "");
	    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	    if(email==''){
	    	jQuery('.g-recaptcha-error').html(error);
	    	return false;
	    }else if(!regex.test(email)){
	    	jQuery('.g-recaptcha-error').html(error1);
	    	return false;
	    }
	    var id = jQuery('#primeur_product_id').val();
        var url = BASE_URL + 'extramanagement/index/sendguestprimeuremail';
        $.ajax({
            showLoader: true,
            url: url,
            data: {isAjax: 1, method: 'POST', id: id, email:email},
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: "POST"
        }).done(function (outputData) {
        	if(outputData.message=='ok'){
        		jQuery('#primeurModal').fadeToggle('fast', 'linear');
            	jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
            	return true;
        	}else{
        		jQuery('#primeur_send_mail_success').text(outputData.message);
        		jQuery('#primeurModal').fadeToggle('fast', 'linear');
	            jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
	            return false;
        	}
        }).fail(function (outputData) {
            jQuery('#primeur_send_mail_success').text('something went wrong. Please check back after few time.');
	        jQuery('#primeurModal').fadeToggle('fast', 'linear');
	        jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
	        return false;
        });
        e.preventDefault();
	});

	//Send email and open the success modal for registered user
	jQuery(document).on('click', '#sendPrimeurEmail',function(e) {
	    var id = jQuery(this).attr('primeur');
	    var email = jQuery(this).attr('email');
	    var name = jQuery(this).attr('name');
	    var url = BASE_URL + 'extramanagement/index/sendprimeuremail';
	    $.ajax({
            showLoader: true,
            url: url,
            data: {isAjax: 1, method: 'POST', id: id, email: email, name: name},
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: "POST"
        }).done(function (outputData) {
        	if(outputData.message=='ok'){
        		if (jQuery('.fullscreen_show').css('display') == 'none') {
                    jQuery('.fullscreen_show').show();
                } else {
                    jQuery('.fullscreen_show').hide();
                }
                jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
            	return true;
        	}else{
        		jQuery('#primeur_send_mail_success').text(outputData.message);
        		if (jQuery('.fullscreen_show').css('display') == 'none') {
                    jQuery('.fullscreen_show').show();
                } else {
                    jQuery('.fullscreen_show').hide();
                }
                jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
	            return false;
        	}
        }).fail(function (outputData) {
            jQuery('#primeur_send_mail_success').text('something went wrong. Please check back after few time.');
	        if (jQuery('.fullscreen_show').css('display') == 'none') {
                jQuery('.fullscreen_show').show();
            } else {
                jQuery('.fullscreen_show').hide();
            }
            jQuery('#primeurSuccessModal').fadeToggle('fast', 'linear');
	        return false;
        });
	    e.preventDefault();
	});
	$("[attribute-code=categories]").removeClass('active');

	/// To active search in mobile device (< 1025)
	if($(window).width() < 1025){
		$('.custom-search-container').empty();
	}

	////////////// end //////////////////////////////////

	$('.language').click(function () {
		var id = this.id;
		var link = $('#'+id).attr('src');
	    $.ajax({
            showLoader: true,
            url: link,
            data: {language: id},
            dataType: 'json',
            type: "POST"
        }).done(function (outputData) {
			return true;
		})

	});


});
