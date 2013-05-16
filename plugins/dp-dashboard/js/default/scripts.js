/**
 * DevPress Dashboard Default theme jQuery.
 */
$j = jQuery.noConflict();

$j(window).load(

	function() {
	
		/* clear after page title */
		$j( '#wpbody-content .wrap h2:first-of-type' ).after('<div class="clear"></div>');
		
		/* subsubsub strip first and last characters, the parentheses of span.count */
		$j( '#wpbody-content .subsubsub li a span.count ').each( function() {
			var currentCount = $j( this ).text();
			var newCount = currentCount.substring(1, currentCount.length-1);
			$j( this ).html( newCount );
		});


	}

);