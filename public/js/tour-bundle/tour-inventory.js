$(document).ready(function(){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var tour_id = $('#tour').data('id');
    $('#countries').select2({
        placeholder : 'Please select countries',
    });
    $('#visas').select2({
        placeholder : 'Please select visa countries',
    });
    $('#tags').select2({
        placeholder : 'Please select tags',
        tags: true
    });
    $('#groups').select2({
        placeholder : 'Please select groups',
        tags: true 
    });
    $('#pictures').select2({
        ajax: {
          method: "POST",
          url: "/api/tour/picture/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, _token: CSRF_TOKEN 
            };
          },
          processResults: function (data, params) {
            return {
              results: data
           
            };
          },
        },
        placeholder : 'Please select pictures',
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: tourPictureTemplateResult,
          templateSelection: tourPictureTemplateResultSelection
    });

  $('#action-quota').select2({
        placeholder : 'Action',
    });

    function tourPictureTemplateResult (state) {
           if (state.loading) {
              return state.text;
            }
          var baseUrl = state.assetPath;
           var markup = 
            '<span>' + 
                '<div  class="row" >' +
                  ' <div class="col-lg-6" > <img style="width:100%" src="' + baseUrl + '/' + state.file_name+ '"  /> </div>' + 
                  ' <div class="col-lg-6"  >' + state.title + '</div>' +
                '</div>' + 
            '</span>';
          
      return markup;

      }
    function tourPictureTemplateResultSelection (state) {
      return state.title ;
    }
    $(document).on('click','.add-countries', function(){
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/country/add",
              data: { country_id : $('#countries').val(), tour_id: tour_id, _token: CSRF_TOKEN },
               dataType: 'JSON',
              beforeSend: function(){
                  thisobj.val("ADDING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {

                var html = "";
                                             
                html+='<tr class="country_id_'+ $('#countries').val() +'" >';
                html+='<td> '+$("#countries  option:selected").text();+' </td>';
                html+='<td> ';
                html+='<input type="radio" name="tour_visa_'+ $('#countries').val() +'" value="1" id="tourvisa-yes"  class="tour_visa_add"  data-country="'+ $('#countries').val() +'" name="tour_visa_'+ $('#countries').val() +'" v /> ';
                html+='<label for="tourvisa-yes" > YES </label>';
                html+='<input type="radio" name="tour_visa_'+ $('#countries').val() +'" value="0" id="tourvisa-no"  checked class="tour_visa_remove" data-country="'+ $('#countries').val() +'" name="tour_visa_'+ $('#countries').val() +'" v /> ';
                html+='<label for="tourvisa-no" > NO </label>';
                html+='</td>'; 
                html+='<td>';
                html+='<input type="checkbox" class="minimal-init tour_visa_manage" data-country="'+ $('#countries').val() +'"  id="manage-'+ $('#countries').val() +'"   /> ';
                html+='</td>';
                html+='<td> <div class="btn-group"><button class="btn btn-default btn-sm remove-countries" name="remove_country_tour" data-country="'+ $('#countries').val()+'"><i class="fa fa-trash-o"></i></button></div></td>';
                html +='</tr>';
                $(".country-tables tbody").prepend(html);
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);
                  $(".minimal-init").iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass   : 'iradio_minimal-blue'
                  })

              }
          });

    });
      
   $(document).on('click','.remove-countries', function(){
      var country_id  = $(this).data("country");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/country/remove",
              data: { country_id : country_id , tour_id: tour_id, _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
                  thisobj.val("DELETING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("REMOVE");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {
                 $(".country_id_"+country_id).remove();
              }
          });


    });
    

   $(document).on('change','.tour_visa_add', function(){
      var country_id  = $(this).data("country");
      var manage  = $("#manage-"+country_id+":checked").length;
      $.ajax({
              method: "POST",
              url: "/api/tour/visa/add",
              data: { country_id : country_id , tour_id: tour_id, manage:manage , _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  return false;
              },
              success: function(event, response) {
              }
          });


    });
      

   $(document).on('ifChanged','.tour_visa_manage', function(event){
      var country_id  = $(this).data("country");
      var manage  = $("#manage-"+country_id+":checked").length;
      $.ajax({
              method: "POST",
              url: "/api/tour/visa/add",
              data: { country_id : country_id , tour_id: tour_id, manage:manage , _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  return false;
              },
              success: function(event, response) {
              }
          });


    });
   $(document).on('change','.tour_visa_remove', function(){
      var country_id  = $(this).data("country");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/visa/remove",
              data: { country_id : country_id , tour_id: tour_id, _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  return false;
              },
              success: function(event, response) {
              }
          });


    });
      
    $(document).on('click','.add-pictures', function(){
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/picture/add",
              data: { picture_id : $('#pictures').val(), tour_id: tour_id, _token: CSRF_TOKEN },
               dataType: 'JSON',
                 beforeSend: function(){
                  thisobj.val("ADDING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {
                  var html = "";

                  html+='<tr class="picture_id_'+$('#pictures').val()+'" >';
                  html+='<td> '+event.data.picture.title+' </td>';
                  html+='<td style="width:5%"><img style="width:100%" src="'+event.data.picture.assetPath+'/'+event.data.picture.file_name+'" /> </td>';
                  html+='<td> '+event.data.picture.description+' </td>';
                  html+='<td> <div class="btn-group"><a class="btn btn-default btn-sm remove-pictures" name="remove_flight_tour" data-picture="'+ $('#pictures').val()+'" ><i class="fa fa-trash-o"></i></a></div></td>';
                  html +='</tr>';
                  $(".pictures-tables tbody").prepend(html);
                    thisobj.val("ADD");
                    thisobj.attr('disabled',false);
              }
            });


    });
  

   $(document).on('click','.remove-pictures', function(){
      var picture_id  = $(this).data("picture");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/picture/remove",
              data: { picture_id : picture_id , tour_id: tour_id, _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
                  thisobj.val("DELETING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("REMOVE");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {
                 $(".picture_id_"+picture_id).remove();
              }
          });


    });
    

  $(document).on('select2:selecting','#tags', function (e) {
      var tagData = e.params.args.data;
        $.ajax({
                method: "POST",
                url: "/api/tour/tag/add",
                data: { tag_id : tagData.id, tour_id: tour_id, _token: CSRF_TOKEN },
                 dataType: 'JSON' ,
                       error: function(event, response) {
                          alert( "Data fail: " + event );
                            return false;
                      },
                      success: function(event, response) {
                      }
                });
  });
  
      
$(document).on('select2:unselecting','#tags', function (e) {
      var tagData = e.params.args.data;
   
            $.ajax({
                method: "POST",
                url: "/api/tour/tag/remove",
                data: { tag_id : tagData.id, tour_id: tour_id, _token: CSRF_TOKEN },
                 dataType: 'JSON',
                 error: function(event, response) {
                        alert( "Data fail: " + event );
                        return false;
                  },
                  success: function(event, response) {
                  }
            });
  });


   
  $(document).on('select2:selecting','#groups', function (e) {
      var groupData = e.params.args.data;
        $.ajax({
                method: "POST",
                url: "/api/tour/group/add",
                data: { group_id : groupData.id, tour_id: tour_id, _token: CSRF_TOKEN },
                 dataType: 'JSON' ,
                       error: function(event, response) {
                          alert( "Data fail: " + event );
                            return false;
                      },
                      success: function(event, response) {
                      }
                });
  });
  
      
$(document).on('select2:unselecting','#groups', function (e) {
      var groupData = e.params.args.data;
   
            $.ajax({
                method: "POST",
                url: "/api/tour/group/remove",
                data: { group_id : groupData.id, tour_id: tour_id, _token: CSRF_TOKEN },
                 dataType: 'JSON',
                 error: function(event, response) {
                        alert( "Data fail: " + event );
                        return false;
                  },
                  success: function(event, response) {
                  }
            });
  });

$(document).on('click','.add-allotment',function(){
  
   var thisobj = $(this);
   var modal =$("#global-modal-tour-inventory");
    modal.addClass("modal-info");
    modal.removeClass("modal-danger");
   modal.find('.modal-title').text("Confirmation");
   modal.find('.modal-body p').text("Are You Sure ?");
   modal.find('.modal-body p').append("<br /><br />Note : <br /><input type='text' name='note' id='note-allot' class='note-allot form-control col-lg-10'   /> <br />");
   modal.find('.modal-footer .save-changes').show();
   modal.find('.modal-footer .save-changes').addClass("add-allotment-saving");
   $("#global-modal-tour-inventory").modal();


});

$(document).on('click','.add-allotment-saving',function(){
    var quota = $("#quota").val();
    var action = $("#action-quota").val();
     $.ajax({
              method: "POST",
              url: "/api/tour-departure/allotment/add",
              data: { quota : quota, action : action, tour_departure_id: $("#tour-departure").data("id"), note: $("#note-allot").val() , _token: CSRF_TOKEN },
               dataType: 'JSON',
                beforeSend: function(){
              },
              error: function(event, response) {
                    var modal =$("#global-modal-tour-inventory");
                      modal.removeClass("modal-info");
                      modal.addClass("modal-danger");
                      modal.find('.modal-title').text("Forbidden");
                      modal.find('.modal-body p').text("Permission Denied");
                      modal.find('.modal-footer .save-changes').hide();
                  return false;
              },
              success: function(event, response) {
                   var html = "";
                   if(event.result){

                      html+='<tr>';
                      html+='<td> '+event.data.timestamp+' </td>';
                      html+='<td> '+event.data.action+' </td>';
                      html+='<td> '+event.data.quota_before+' </td>';
                      html+='<td> '+event.data.quota_mutation+' </td>';
                      html+='<td> '+event.data.quota_after+' </td>';
                      html+='<td> '+event.data.user_mail+' </td>';
                      html+='<td> '+event.data.reserved_before+' </td>';
                      html+='<td> '+event.data.reserved_mutation+' </td>';
                      html+='<td> '+event.data.reserved_after+' </td>';
                      html+='<td> '+ (parseInt(event.data.quota_after) - parseInt(event.data.reserved_after)) +' </td>';
                      html+='<td> '+event.data.note+' </td>';
                      html +='</tr>';


                     $(".allotment-tables tbody").prepend(html);
                      $("#global-modal-tour-inventory").modal('hide');
                   }else{
                      var modal =$("#global-modal-tour-inventory");
                      modal.removeClass("modal-info");
                      modal.addClass("modal-danger");
                      modal.find('.modal-title').text("Forbidden");
                      modal.find('.modal-body p').text("Permission Denied");
                      modal.find('.modal-footer .save-changes').hide();
                   }

                    $(".save-changes").removeClass("add-allotment-saving");

              }
            });

  });


    $(document).on('click','.add-highlights', function(){
      var thisobj  = $(this);
      var startat = $("#date_period_highlight").data('start');
      var endat = $("#date_period_highlight").data('end');
      var published = $("#publish-highlight:checked").length;
      var client = $("#apiuser").val();
      $.ajax({
              method: "POST",
              url: "/api/tour/highlight/add",
              data: { 
                        starts_at : startat, 
                        ends_at : endat, 
                        published : published, 
                        client_id : client, 
                        tour_id: tour_id, 
                        _token: CSRF_TOKEN },
               dataType: 'JSON',
              beforeSend: function(){
                  thisobj.val("ADDING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {
                var checkedpublished = '' ;
                if(event.data.published==1) checkedpublished = 'checked';
                var html = "";
                                             
                html+='<tr class="highlight_id_'+ event.data.id +'" >';
                html+='<td> '+ event.data.starts_at +' </td>';
                html+='<td> '+ event.data.ends_at +' </td>';
                html+='<td> <input type="checkbox" '+checkedpublished+' class="minimal-init" readonly /> </td>';
                html+='<td> '+ event.data.client.client_name +' </td>';
                html+='<td> <div class="btn-group"><button class="btn btn-default btn-sm remove-highlights" name="remove_country_tour" data-highlight="'+ event.data.id +'"><i class="fa fa-trash-o"></i></button></div></td>';
                html +='</tr>';

                $(".highlight-tables tbody").prepend(html);
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);

                  $(".minimal-init").iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass   : 'iradio_minimal-blue'
                  })


              }
          });

    });
      
   $(document).on('click','.remove-highlights', function(){
      var hihglight_id  = $(this).data("highlight");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour/highlight/remove",
              data: { id : hihglight_id , tour_id: tour_id, _token: CSRF_TOKEN },
              dataType: 'JSON',
              beforeSend: function(){
                  thisobj.val("DELETING..");
                  thisobj.attr('disabled',true);
              },
              error: function(event, response) {
                  alert( "Data fail: " + event );
                  thisobj.val("REMOVE");
                  thisobj.attr('disabled',false);
                  return false;
              },
              success: function(event, response) {
                 $(".highlight_id_"+hihglight_id).remove();
              }
          });


    });


   $(document).on('select2:selecting','#date-departure-pick', function (e) {
      var uri = $(this).data("uri");
      var dateDepart = e.params.args.data;
      window.location.href = uri + "?" + "date-departure-pick=" + dateDepart.id;

   });
     $(document).on('select2:selecting','#copy-date-departure-pick', function (e) {
      var uri = $(this).data("uri");
      var dateDepart = e.params.args.data;
        $("#copy-date-departure-pick").data("ddd",dateDepart.id);
   });
   
   $(document).on('click','.add_copy_departure', function(){
      var uri = "/api/tour/inventory-tour/copy";
    var ddd =  $("#copy-date-departure-pick").data("ddd");
    var ttt =  $(".add_copy_departure").data("ttt");

    var sss =  $("#departure_copy_to_date").val();
      window.location.href = uri + "?id=" + ttt + "&copy-id=" + ddd + "&to-date=" + sss;

    });


    $(document).on('ifChanged','.published-departure', function(event){
        
          var thisobj  = $(this);
          var published = 0;
          if(event.target.checked) published = 1;
              $.ajax({
                      method: "POST",
                      url: "/api/tour/departure-tour/publish",
                      data: { id : thisobj.data("departureid"), published : published ,  _token: CSRF_TOKEN },
                      dataType: 'JSON',
                      beforeSend: function(){
                  
                      },
                      error: function(event, response) {
                          alert( "Data fail: " + event );
                          return false;
                      },
                      success: function(event, response) {

                   }
              });
            });



});