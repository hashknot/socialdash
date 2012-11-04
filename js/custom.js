$('.returnTopAction').live('click', function() {
   $('html, body').animate({scrollTop: '0'}, 700);
});
$('#gallery-page').live('pageshow', function () { 
		$myPhotoSwipe = $(".gallery a").photoSwipe({ enableMouseWheel: false , enableKeyboard: false });});
		
$('#send').live("click", function() {
				var url = 'send.php';
				var error = 0;
				var $contactpage = $(this).closest('.ui-page');
				var $contactform = $(this).closest('.contact-form');
				$('.required', $contactform).each(function(i) {
					if($(this).val() === '') {
						error++;
					}
				});
				// each
				if(error > 0) {
					alert('Please fill in all the mandatory fields. Mandatory fields are marked with an asterisk *.');
				} else {
					var firstname = $contactform.find('input[name="firstname"]').val();
					var surname = $contactform.find('input[name="surname"]').val();
					var state = $contactform.find('select[name="state"]').val();
					var mobilephone = $contactform.find('input[name="mobilephone"]').val();
					var email = $contactform.find('input[name="email"]').val();
					var message = $contactform.find('textarea[name="message"]').val();

					//submit the form
					$.ajax({
						type : "GET",
						url : url,
						data : {
							firstname : firstname,
							surname : surname,
							state : state,
							mobilephone : mobilephone,
							email : email,
							message : message
						},
						success : function(data) {
							if(data == 'success') {
								// show thank you
								$contactpage.find('.contact-thankyou').show();
								$contactpage.find('.contact-form').hide();
							} else {
								alert('Unable to send your message. Please try again.');
							}
						}
					});
					//$.ajax

				}
				return false;
			});
