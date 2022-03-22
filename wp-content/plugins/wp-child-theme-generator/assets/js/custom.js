( function( $ ) {

  $(document).ready(function($){
  	$('#custom-screenshot').hide();
    $('#custom-child').hide();
    $('#theme-info').show();
  $('#custom-select').on('change', function() {
       $var = $(this).val();

       $('#parent').val($var);
       $('#child-author').val($(this).find('option:selected').attr('data-author'));
       $('#child-name').val($(this).find('option:selected').attr('data-name') + ' Child');
       $('#child-description').val($(this).find('option:selected').attr('data-description'));
       $('#image-holder').attr('src','file://'+($(this).find('option:selected').attr('data-screen')));
       

       $('#theme-info').hide();
       $('#custom-child').fadeIn('slow');
       
      });

	$("#fileUpload").on('change', function () {

     //Get count of selected files
     var countFiles = $(this)[0].files.length;

     var imgPath = $(this)[0].value;
     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
     var image_holder = $("#image-holder");
     image_holder.empty();

     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
       $("#custom-child").unbind('submit');
         if (typeof (FileReader) != "undefined") {

             //loop for each file selected for uploaded.
             for (var i = 0; i < countFiles; i++) {

                 var reader = new FileReader();
                 reader.onload = function (e) {
                     $("<img />", {
                         "src": e.target.result,
                             "class": "thumb-image"
                     }).appendTo(image_holder);
                 }

                 image_holder.show();
                 reader.readAsDataURL($(this)[0].files[i]);
             }

         } else {
             alert("This browser does not support FileReader.");
         }
     } else {
         alert("Please select a proper image file");
          $("#custom-child").submit(function(event){
            event.preventDefault();
          });
         
     }
 });
	$('#child-screenshot').change(function(){
        if (this.checked) {
            $('#custom-screenshot').fadeOut('slow');
            $("#custom-child").unbind('submit');

        }
        else {
          $("#custom-child").submit(function(event){
            event.preventDefault();
          });
            $('#custom-screenshot').fadeIn('slow');
        }                   
    });

  });
} )( jQuery );
