//=============================================================================
// Main JavaScript File
//=============================================================================
jQuery(function($){
	//=============================================================================
	// Cache Elements
	//=============================================================================
	$window 	= $(window);
	$body 		= $('body');
	_$chosen 	= $('[rel="chosen"]');
	_$validate 	= $('[validate]');

	//###########################################################################
	// REL Constructors
	//###########################################################################
	//=============================================================================
	// Chosen (http://harvesthq.github.com/chosen/)
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// no-results-text="STRING"		Text to display when no results are found
	//=============================================================================
	if(_$chosen.length > 0){
		_$chosen.each(function(){
			var $this = $(this);
			$this.chosen({
				no_results_text: 	$this.attr('no-results-text') || 'No results found',
				placeholder_text: 	$this.attr('placeholder') || 'Select options'
			});
		});
	}

	//###########################################################################
	// jscrollpane constructors
	//###########################################################################
	$('[rel="jscrollpane"]').jScrollPane();


	//###########################################################################
	// AJAX and Validation Forms
	// Both do live validation but rel="ajax" loads information from the server
	//###########################################################################
	$('form[rel="ajax"]').each(function(){
		var $this = $form = $(this);

		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Add attributes
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		if(!$this.attr('action')) $this.attr('action', bloginfo.ajax_url);	//Set a default action
		$this.data('validate', $this.find('[validate]'));					//Caches validation elements
		$this.data('message-ajax', $this.find('.message-ajax'));			//Caches the .message-ajax div

		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// * Assign this form as the parent for each of the [validate] elements
		// * Cache the label text, if any
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		if($this.data('validate').each(function(){
			var $this = $(this);
			$this.data('parent', $form);
			$this.data('label', $form.find('[for="'+$this.attr('id')+'"]'));

			//=============================================================================
			// Create the error message
			//=============================================================================
			if($this.data('label')) {
				switch($this.attr('validate')){
					case 'email':
						$this.after(
							'<div class="message-error clear">' 
							+ _validation_error_messages($this, i18n.form_validation_bad_email) 
							+ '</div>'
						);
					break;
					//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					// Text
					//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					default:
						$this.after(
							'<div class="message-error clear">' 
							+ _validation_error_messages($this, i18n.form_validation_no_text) 
							+ '</div>'
						);
					break;
				}
				$this.data('next', $this.next());
				$this.data('next').css('height', $this.data('next').height());
			}

			//=============================================================================
			// Validate on blur
			//=============================================================================
			$this.blur(function(){
				_validate_form_element($this);
			});
		}));

		//=============================================================================
		// Create the AJAX message form
		//=============================================================================
		if(!$this.data('message-ajax').length){
			$this.append('<div class="message-ajax clear"></div>');
			$this.data('message-ajax', $this.find('.message-ajax'));
		}

		$this.ajaxForm({
			//=============================================================================
			// Validate Form
			//=============================================================================
			beforeSubmit: function(arr, $form, options){
				var pass = true;	//Determine if we have passed the form validation
				if($form.data('validate').length){
					$form.data('validate').each(function(){
						var $this = $(this);
						if(!_validate_form_element($this)){pass = false;}
					});
				}
				return pass;
			},

			//=============================================================================
			// Successful submit
			//=============================================================================
			success: function(response, status){
				var json = $.parseJSON(response);

				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Callback Function
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if($this.attr('callback')) window[$this.attr('callback')]($this, response, status, json);

				//=============================================================================
				// Check Status Codes
				//=============================================================================
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// No error code, or 0 returned
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if(!json['status']){
					$this.data('message-ajax')
						.html(json['message'])
						.slideDown()
						.removeClass('error warning success')
						.addClass('error');
				}else{
					switch(json['status']){
						//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
						// Success, replace form with message
						//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
						default:
							$this.data('message-ajax').slideUp();
							$this.slideUp(function(){
								$this.after('<div class="message-ajax replacement success hidden">' + json['message'] + '</div>')
									.next().slideDown()
									.end().remove();
							});
						break; 
					}
				}
			}
		});
	});
});


//###########################################################################
// Validation Functions
//###########################################################################
//=============================================================================
// Validate form element
//=============================================================================
function _validate_form_element($this){
	var pass = true;
	switch($this.attr('validate')){
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Email
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		case 'email':
			pass = /\S+@\S+\.\S+/.test($this.val());
		break;
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Text
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		default:
			pass = _.str.trim($this.val());
		break;
	}
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Go/No-Go
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(pass) $this.data('next').slideUp();
	else $this.data('next').slideDown();
	return pass;
}
//=============================================================================
// Display validation error messages
//=============================================================================
function _validation_error_messages($this, message){
	message = message.replace('%label_text%', '<b>' + $this.data('label').text() + '</b>');
	return message;
}



//###########################################################################
// Common Callbacks
//###########################################################################
function callback_commented(){
	$('.comment-reply-link').each(function(){
		$(this).parent().remove();
	});
}