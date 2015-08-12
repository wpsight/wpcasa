jQuery(document).ready(function($) {
	
	/**
	 * Title order select go
	 * to URL on change option
	 *
	 * @since 1.2
	 */
	function wpsight_select_order() {
      $('.listings-sort select').on('change', function () {
          var url = $(this).val();
          if (url) {
              window.location = url;
          }
          return false;
      });
    }
    
    wpsight_select_order();
	
	/** ADVANCED SERACH */
	
	function wpsight_advanced_search() {
	
		if($.cookie(wpsight_localize.cookie_search_advanced) != 'closed') {
			$('.listings-search-advanced.open').show();
		}
	
		if ($.cookie(wpsight_localize.cookie_search_advanced) && $.cookie(wpsight_localize.cookie_search_advanced) == 'open') {
		    $('.listings-search-advanced').show();
		    $('.listings-search-advanced-toggle').addClass('open');
		}
		
		$('.listings-search-advanced-toggle').click(function () {
		    if ($('.listings-search-advanced').is(':visible')) {
		    	$.cookie(wpsight_localize.cookie_search_advanced, 'closed',{ expires: 60, path: wpsight_localize.cookie_path });
		        $('.listings-search-advanced .listings-search-field').animate(
		            {
		                opacity: '0'
		            },
		            150,
		            function(){           	
		                $('.listings-search-advanced-toggle').removeClass('open');
		                $('.listings-search-advanced').slideUp(150);	 
		            	$('.listings-search-advanced option:selected').removeAttr('selected');
		            	$('.listings-search-advanced input').attr('checked', false);
		            	$('.listings-search-advanced input').val('');
		            }
		        );
		    }
		    else {
		        $('.listings-search-advanced').slideDown(150, function(){
		        	$.cookie(wpsight_localize.cookie_search_advanced, 'open',{ expires: 60, path: wpsight_localize.cookie_path });
		            $('.listings-search-advanced div').animate(
		                {
		                    opacity: '1'
		                },
		                150
		            );	            
		    		$('.listings-search-advanced-toggle').addClass('open');
		        });
		    }   
		});
	
	}
	
	wpsight_advanced_search();
	
	/** RESET SERACH */
	
	function wpsight_reset_form($form) {
        $form.find('.listings-search-field .text').val('');
        $form.find('.listings-search-field-operator').val('');
        $form.find('select').val('');
        $form.find('select option[data-default="true"]').attr('selected',true);
        $form.find('input:checkbox').removeAttr('checked');
        $form.find('input:radio').removeAttr('checked');
        $form.find('input:checkbox[data-default="true"]').attr('checked',true);
        $form.find('input:radio[data-default="true"]').attr('checked',true);
    }
	
	function wpsight_reset_search() {
	
		$('.listings-search-reset').click(function () {			
			wpsight_reset_form($('.wpsight-listings-search'));
			$.cookie(wpsight_localize.cookie_search_query, null, { expires: 30, path: wpsight_localize.cookie_path });
			$(this).animate({ opacity: '0' },150);
		});
	
	}
	
	wpsight_reset_search();
	
});