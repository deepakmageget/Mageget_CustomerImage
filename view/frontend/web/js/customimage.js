require(['jquery'], function($) {
	$("#customerImageUpload").css("display", "none");
	$("#customerImage").change(function() {
		var fileName = $(this).val();
		if (fileName.length > 0) {
			var ext = fileName.split('.').pop().toLowerCase();
			if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
				$('.customerimageerror span').text('* Please Select png, jpg or jpeg image Only !');
				$("#customerImageUpload").css("display", "none");
			}
			else {
				$("#customerImageUpload").css("display", "block");
				$('.customerimageerror span').text('');
				$(this).parent().children('span').css("color", "white");
				$(".fileContainer").css("background", "black");
				$(this).parent().children('span').html("Change Your Profile Image");
			}
		}
		else {
			$(this).parent().children('span').html("Choose Your Profile Image");
		}
	});
	//file input preview
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var ext = $("#customerImage").val().split('.').pop().toLowerCase();
				if ($.inArray(ext, ['png', 'jpg', 'jpeg']) !== -1) {
					$('.logoContainer img').attr('src', e.target.result);
					$("#customerImageUpload").click(function() {
						var baseurl = $('#baseUrl').val();
						var url = baseurl + 'customerimage/customer/save';
						$('#customerimageform').submit(function(e) {
							$('#customerImageUpload').prop('disabled', true);
							$('#customerImageUpload').text('Please Wait...');
							$.ajax({
								url: url,
								type: 'POST',
								data: new FormData(this),
								processData: false,
								contentType: false,
								success: function(result) {
									$('#customerImageUpload').css("display", "none");
									$('.fileContainer span').css("color", "white");
									$('.fileContainer span').text("Choose Another Profile Image");;
								}
							})
							e.preventDefault();
						});
					});
				}
				else {
					$('.logoContainer img').attr('src', 'http://img1.wikia.nocookie.net/__cb20130901213905/battlebears/images/9/98/Team-icon-placeholder.png');
					$('.fileContainer span').html("Choose Valid Image");
				}
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	$("#customerImage").change(function() {
		readURL(this);
	});
	$("#customerImageDel").click(function() {
		var baseurl = $('#baseUrl').val();
		var delurl = baseurl + 'customerimage/customer/deleteImage';
		$.ajax({
			url: delurl,
			type: 'POST',
			success: function(result) {
				setTimeout(function() {
					window.location.reload(1);
				}, 2000);
			}
		})
	});
});