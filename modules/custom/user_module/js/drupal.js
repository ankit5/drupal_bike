
/* function preventBack() { window.history.forward(); }  
    setTimeout("preventBack()", 0);  
    window.onunload = function () { null };*/
  


(function($, Drupal, drupalSettings) {

const images = document.querySelectorAll('img');

images.forEach(img => {
  img.addEventListener('error', function handleError() {
    const defaultImage =
      'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg';

    img.src = defaultImage;
    img.alt = 'default';
  });
});


     $("#toolbar-bar").click(function(event){
   // alert("hi");
});

$("#edit-field-state-value").change(function(e) {
var state = this.value;
    
  var data = { 
            state: this.value
        };
        var baseUrl = drupalSettings.path.baseUrl;
        var hash = drupalSettings.api_Authorization;
        //alert(data);
    e.preventDefault();
     
    $.ajax({
        type: "GET",
        url: baseUrl + "restapi/get_city/" + state,
        //data:JSON.stringify(data),
        //contentType: "application/json; charset=UTF-8",
        dataType: "json",
        beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic " + hash);
     var progressElement = $(Drupal.theme('ajaxProgressThrobber'));
//$('.js-form-item-field-state-value').html('Fetching Cities...');
    $('.js-form-item-field-state-value').append(progressElement);
},
        success: function(result) {
          //var json = $.parseJSON(result);
         // var json_obj = result.reverse();;
        // var objAssetSelection = $.parseJSON(result);
        
        // console.log(result);
        //  alert(JSON.stringify(json_obj));
          $('#edit-field-store-city-value')
     .find('option')
    .remove()
    .end();
    $('#edit-field-store-city-value').append("<option value='All'>-Any-</option>");
          $.each(result, function (key, value) {
    $('#edit-field-store-city-value').append("<option value='"+ key+"'>" + value+ "</option>");
    //this ensure the ajax call, values print on console
  

});
           $('.ajax-progress-throbber').remove();
           // alert("success");
            //location.reload();
        },
        error: function(result) {
           alert(result);

        }
    });
});
  
$("#edit-field-state").change(function(e) {
        var state = this.value;
        var data = {
            state: this.value
        };
        var baseUrl = drupalSettings.path.baseUrl;
        var hash = drupalSettings.api_Authorization;
        // alert(data);
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: baseUrl + "restapi/get_city/" + state,
            //data:JSON.stringify(data),
            //contentType: "application/json; charset=UTF-8",
            dataType: "json",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Basic " + hash);
                var progressElement = $(Drupal.theme('ajaxProgressThrobber'));
                //$('.js-form-item-field-state-value').html('Fetching Cities...');
                $('.js-form-item-field-state').append(progressElement);
                $('#edit-field-store-city').hide();
            },
            success: function(result) {
                //var json = $.parseJSON(result);
                // var json_obj = result.reverse();;
                // var objAssetSelection = $.parseJSON(result);
               // console.log(result);
                // alert(JSON.stringify(json_obj));
                
                $('#edit-field-store-city')
                    .find('option')
                    .remove()
                    .end();
                //$('#edit-field-store-city-value').append("<option value='All'>-Any-</option>");
                $.each(result, function(key, value) {
                    $('#edit-field-store-city').append("<option value='" + key + "'>" + value + "</option>");
                    //this ensure the ajax call, values print on console
                });
                $('.ajax-progress-throbber').remove();
                $('#edit-field-store-city').show();
                // alert("success");
                //location.reload();
            },
            error: function(result) {
                alert(result);
            }
        });
    });




$("form").submit( function(e) {
        $("form .form-text, form .form-textarea").each(function() {
          var reg =/<(.|\n)*?>|[()]+["-']/g; 
            var login = drupalSettings.login;
           
            if (reg.test(this.value) == true && login!='1') {
               // alert('HTML Tag are not allowed');
               // this.focus();
               // e.preventDefault();
                //return false;
            }
            
        });
        return true;
    });

Drupal.behaviors.customCKEditorConfig = {
    attach: function (context, settings) {
      if (typeof CKEDITOR !== "undefined") {
        //CKEDITOR.config.contentsLangDirection = 'rtl';
        //CKEDITOR.config.protectedSource = [/\n/g];
       // CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
       CKEDITOR.config.basicEntities = false;
      }
    }
  }

 Drupal.behaviors.cancel_order = {
        attach: function (context, settings) {
  var befSettings = drupalSettings.better_exposed_filters;
      if (befSettings && befSettings.datepicker && befSettings.datepicker_options && $.fn.datepicker) {
     
$(".bef-datepicker").attr("autocomplete", "off");
     $('input[name="created_at_ymd"].bef-datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
 }
       $("#cancel_order").click(function(e) {
         var data = { 
            id: $("#order_id").val(), 
            cancel_reason: 'string',// < note use of 'this' here
            cancel_description: $("#cancel_message").val()
        };
        var api_url = drupalSettings.api_url;
        var hash = drupalSettings.api_Authorization;
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: api_url + "customer/order/cancel-order-cms",
        data:JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        dataType: "json",
        beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic " + hash);
},
        success: function(result) {
            alert(result.message);
            location.reload();
        },
        error: function(result) {
           alert(result.message);

        }
    });
});



       $("#help_status").click(function(e) {
       // alert($("#help_status_value").val());
         var data = { 
            request_id: $("#help_status_id").val(), 
            status: $("#help_status_value").val(),// < note use of 'this' here
            remark: $("#remark").val(),
            resolved_by:'Admin',
            resolved_date:$("#resolved_date").val()
        };
        var api_url = drupalSettings.api_url;
        var hash = drupalSettings.api_Authorization;
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: api_url + "customer/product/update-status-remark",
        data:JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        dataType: "json",
        beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic " + hash);
},
        success: function(result) {
            alert(result.message);
            location.reload();
        },
        error: function(result) {
           alert(result.message);

        }
    });
});




        }
    };

  $("#edit-field-image-0-upload").attr("multiple",false);

  $('.quick_clone').click(function(){
    return confirm("Are you sure to Duplicate this section, Please confirm?");
})
   $('li.quick-clone a').click(function(){
    return confirm("Are you sure to Duplicate this section, Please confirm?");
})

 $("#or_status").change(function(e) {
         // alert($("#or_status").val());
         var data = { 
            id: $("#order_id").val(), 
            shipping_status: $("#or_status").val()
        };
        var api_url = drupalSettings.api_url;
        var hash = drupalSettings.api_Authorization;
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: api_url + "customer/order/change-order-status-cms",
        data:JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        dataType: "json",
        beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic " + hash);
      var progressElement = $(Drupal.theme('ajaxProgressThrobber'));
$('.progress_bar').html('Sending...');
    $('.progress_bar').append(progressElement);
},
        success: function(result) {
            alert(result.message);
            location.reload();
        },
        error: function(result) {
           alert(result.message);

        }
    });
});

$("#mcsl_mail").click(function(e) {
         var data = { 
            order_id: $("#mcsl_order_id").val()
        };
        var api_url = drupalSettings.api_url;
        var hash = drupalSettings.api_Authorization;
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: api_url + "customer/order/resend-order-email",
        data:JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        dataType: "json",
        beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic " + hash);
    
     var progressElement = $(Drupal.theme('ajaxProgressThrobber'));
$('.view-mcsl-mail').html('Sending...');
    $('.view-mcsl-mail').append(progressElement);
},
        success: function(result) {
            alert(result.message);
            location.reload();
        },
        error: function(result) {
           alert(result.message);

        }
    });
});



})(jQuery, Drupal, drupalSettings);;