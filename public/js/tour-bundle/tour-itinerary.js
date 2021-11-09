
$(document).ready(function(){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var tour_id = $('#tour').data('id');
    $('#airlines').select2({
        placeholder : 'Please select airlines', 
    });
    $('#origins').select2({
        placeholder : 'Please select origins', 
    });
    $('#destinations').select2({
        placeholder : 'Please select destinations', 
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
    $('#places').select2({
        placeholder : 'Please select places', 
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
 function tourPlaceTemplateResult (state) {
          if (!state.id) {
            return state.text;
          } 
          var baseUrl = "/data/img";
          var $state = $(
            '<span>' + 
                '<div style="height:100px" class="row" >' +
                  ' <div class="col-lg-6" > <img style="height:100px" src="' + baseUrl + '/' + state.element.getAttribute('data-place') + '"  /> </div>' + 
                  ' <div class="col-lg-6" >' + state.text + '</div>' +
                '</div>' + 
            '</span>'
          );
      return $state;

      }
$(document).on('click','.add-flights', function(){

      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour-itinerary/flight/add",
              data: { 
                airline_code : $('#airlines').val(), 
                origin_code:  $('#origins').val(), 
                destination_code :  $('#destinations').val() , 
                departs_at :  $("#depart-arrive-period").data("start"), 
                arrives_at : $("#depart-arrive-period").data("end") , 
                tour_itinerary_id: $("#tour-itinerary").data("id") , 
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
                var html = "";

                html+='<tr class="flight_code_'+event.data.id+'" >';
                html+='<td> '+$("#airlines  option:selected").text();+' </td>';
                html+='<td> '+$("#origins  option:selected").text();+' </td>';
                html+='<td> '+$("#destinations  option:selected").text()+' </td>';
                html+='<td> '+$("#depart-arrive-period").data("start")+' </td>';
                html+='<td> '+$("#depart-arrive-period").data("end")+' </td>';
                html+='<td> <div class="btn-group"><a class="btn btn-default btn-sm remove-flights" name="remove_flight_tour" data-airline="'+ $('#airlines').val()+'" data-origin="'+ $('#origins').val()+'" data-destination="'+ $('#destinations').val()+'"  data-itinerary="'+$("#tour-itinerary").data("id")+'" data-id="'+event.data.id+'"><i class="fa fa-trash-o"></i></a></div></td>';
                html +='</tr>';
                $(".flight-tables tbody").prepend(html);
                  thisobj.val("ADD");
                  thisobj.attr('disabled',false);


              }
          });

    });
      
   $(document).on('click','.remove-flights', function(){
      var airline_code  = $(this).data("airline");
      var origin_code  = $(this).data("origin");
      var destination_code  = $(this).data("destination");
      var itinerary_id =  $(this).data("itinerary");
      var id =  $(this).data("id");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour-itinerary/flight/remove",
              data: { airline_code : airline_code , origin_code: origin_code, destination_code: destination_code ,tour_itinerary_id : itinerary_id, id: id, _token: CSRF_TOKEN },
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
                 $(".flight_code_"+id).remove();
              }
          });


    });

  $(document).on('click','.add-pictures', function(){
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour-itinerary/picture/add",
              data: { picture_id : $('#pictures').val(), tour_itinerary_id: $("#tour-itinerary").data("id"), _token: CSRF_TOKEN },
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
              url: "/api/tour-itinerary/picture/remove",
              data: { picture_id : picture_id , tour_itinerary_id: $("#tour-itinerary").data("id"), _token: CSRF_TOKEN },
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
    
  $(document).on('click','.add-places', function(){
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour-itinerary/place/add",
              data: { 
                place_id : $('#places').val(), 
                stop : $("#stop:checked").length ,  
                photo_session : $("#photo_session:checked").length, 
                tour_itinerary_id: $("#tour-itinerary").data("id"), 
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
                  var html = "";
                  stopChecked = "";
                  photoSessionChecked = "";
                  if(event.data.stop == 1) stopChecked = "checked";
                  if(event.data.photo_session == 1) photoSessionChecked = "checked";
                  html+='<tr class="place_id_'+event.data.id +'" >';
                  html+='<td> '+event.data.place.name+' </td>';
                  html+='<td>  <input  type="checkbox" readonly class="minimal-init" name="stopitem"  '+stopChecked+' /> </td>';
                  html+='<td>  <input  type="checkbox" readonly class="minimal-init" name="photosessionitem"  '+photoSessionChecked+' />  </td>';
                  html+='<td> <div class="btn-group"><a class="btn btn-default btn-sm remove-places" name="remove_place_tour" data-place="'+ event.data.id +'" ><i class="fa fa-trash-o"></i></a></div></td>';
                  html +='</tr>';
                  $(".places-tables tbody").prepend(html);
                    thisobj.val("ADD");
                    thisobj.attr('disabled',false);
                    $(".minimal-init").iCheck({
                      checkboxClass: 'icheckbox_minimal-blue',
                      radioClass   : 'iradio_minimal-blue'
                    });
              }
            });


    });
  

   $(document).on('click','.remove-places', function(){
      var id  = $(this).data("place");
      var thisobj  = $(this);
      $.ajax({
              method: "POST",
              url: "/api/tour-itinerary/place/remove",
              data: { id : id  , _token: CSRF_TOKEN },
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
                 $(".place_id_"+id).remove();
              }
          });


    });
    
    
});
