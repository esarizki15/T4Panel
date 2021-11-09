$(document).ready(function(){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    $("#adult_suplement_price").on("keyup",function(){
       $("#adult_single_price").val(parseInt($(this).val(),10) + parseInt($("#adult_twin_sharing_price").val()));

    });


    $("#adult_twin_sharing_price").on("keyup",function(){
       $("#adult_single_price").val(parseInt($("#adult_suplement_price").val(),10) + parseInt($("#adult_twin_sharing_price").val()));

    });


});