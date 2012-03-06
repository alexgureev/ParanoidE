(function($)
{
    JQFUNCTIONS =
    {
        settings:
        {
			ajaxImage: 'http://www.jquery4u.com/demos/ajax/loading.gif'
        },

        init: function()
        {
			//button events
			$('button').live('click', function(e) {
				e.preventDefault();
				eval('JQFUNCTIONS.runFunc["'+$(this).attr("id")+'"]();');
			});
        },

        runFunc:
        {

            "ajaxphp": function()
            {
				
				$('#ajax_div').html('<img src="http://www.jquery4u.com/demos/ajax/loading.gif" />');
				$.ajax({
					  url: '/index.php?method=ajax_request',
					  type: 'POST',
					  data: 'ajax=some_vars',
					  success: function(data) {
						//called when successful
						$('#ajax_div').html(data);
					  },
					  error: function(e) {
						//called when there is an error
						//console.log(e.message);
					  }
					});
            }
        }

    }

})(jQuery);


