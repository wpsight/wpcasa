(function($) {
	
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
	
}(jQuery));