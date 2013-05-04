//###########################################################################
// Used in the Products CPT
// :: allows us to preview the post thumbnail
//###########################################################################
jQuery(function($){
	$productPosition = $('#_product_position');

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Add the preview image
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$('#product-thumbnail-preview').append('<div id="product-thumbnail-preview-image"><div></div></div>');
	$productPreview = $('#product-thumbnail-preview-image');
	$productPreviewInner = $('#product-thumbnail-preview-image div');

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Set the starting offsets
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$productPreview.css('backgroundPosition', ($productPosition.val() * 100).toString() + 'px 100px' );
	$productPreviewInner.css('backgroundPosition', ($productPosition.val() * 100).toString() + 'px 0px' );

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Update the image when the user selects a new offset
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$productPosition.keyup(function(){
		$productPreview.css('backgroundPosition', ($(this).val() * 100).toString() + 'px 100px' );
		$productPreviewInner.css('backgroundPosition', ($(this).val() * 100).toString() + 'px 0px' );
	});

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Hover fade effect
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$productPreviewInner.hover(function(){
		$(this).stop().fadeTo(250, 0);
	}, function(){
		$(this).stop().fadeTo(250, 1);
	})
});