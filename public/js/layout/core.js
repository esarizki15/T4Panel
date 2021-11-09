$(document).ready( function () {

	//iCheck for checkbox and radio inputs
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	  checkboxClass: 'icheckbox_minimal-blue',
	  radioClass   : 'iradio_minimal-blue',
    disabledCheckboxClass: 'icheckbox_minimal-grey',
    disabledRadioClass: 'iradio_minimal-grey',

	})
	//Red color scheme for iCheck
	$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	  checkboxClass: 'icheckbox_minimal-red',
	  radioClass   : 'iradio_minimal-red'
	})
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	  checkboxClass: 'icheckbox_flat-green',
	  radioClass   : 'iradio_flat-green'
	})
	//Initialize Select2 Elements
	$('.select2').select2({ minimumResultsForSearch: 5 })

    $('.data-table-init').DataTable( {
        "paging":   false,
        "searching": false,
         "order": [[ 0, 'desc' ]]
    } );

    
    $('.wysihtml5-editor').wysihtml5()

     $(".date-picker").datepicker( {
              autoclose: true 
     });
   
    $(".time-picker").timepicker({
        showMeridian: false,
        showSeconds: true,
      });
       $(".date-picker-copy").datepicker(   { format: 'yyyy-mm-dd'   });
  	$(".date-picker-range").daterangepicker({
      ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        // "startDate": "09/01/2020",
        // "endDate": moment()
      }
        );
    
    $('.date-picker-range').on('apply.daterangepicker', function(ev, picker) {
   		  $(".date-picker-range").data("start",picker.startDate.format('YYYY-MM-DD') );
        	$(".date-picker-range").data("end",picker.endDate.format('YYYY-MM-DD') );
  	});
    $(".date-time-picker-range").daterangepicker({ timePicker: true, timePickerIncrement: 5 ,  timePicker24Hour: true, locale: {
        format: 'YYYY/MM/DD HH:mm:ss'
    } });
    $('.date-time-picker-range').on('apply.daterangepicker', function(ev, picker) {
        $(".date-time-picker-range").data("start",picker.startDate.format('YYYY-MM-DD HH:mm:ss') );
          $(".date-time-picker-range").data("end",picker.endDate.format('YYYY-MM-DD HH:mm:ss') );
    });
    	 
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })
   

} );