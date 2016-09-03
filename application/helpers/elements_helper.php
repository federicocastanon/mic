<?php 
function daterangepicker($target, $start, $end, $format = 'YYYY-MM-DD') { 
	$target = base_url($target);
	$f_start = date("F j Y",strtotime($start));
	$f_end = date("F j Y",strtotime($end));
	$echo = '<script type="text/javascript" src="' . assets_url('js/moment.min.js') . '"></script>';
	$echo.= '<script type="text/javascript" src="' . assets_url('js/daterangepicker.js') . '"></script>';
	$echo.=<<<EOD
<script type='text/javascript'>
	  $(document).ready(function() {
	$('#reportrange').daterangepicker(
	 {
	    ranges: {
	       'Today': [new Date(), new Date()],
	       'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
	       'Last 7 Days': [moment().subtract('days', 6), new Date()],
	       'Last 30 Days': [moment().subtract('days', 29), new Date()],
	       'This Month': [moment().startOf('month'), moment().endOf('month')],
	       'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
	       'Lifetime': [moment().subtract('year', 5).startOf('month'), moment().add('month', 1).endOf('month')],
	    },
	    opens: 'right',
	    format: '$format',
	    separator: ' to ',
	    startDate: moment('$f_start'),
	    endDate: moment('$f_end'),
	    //minDate: moment().subtract('days', 179).format('$format'),
	    //maxDate: moment().format('$format'),
	    locale: {
	        applyLabel: 'Submit',
	        fromLabel: 'From',
	        toLabel: 'To',
	        customRangeLabel: 'Custom Range',
	        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
	        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	        firstDay: 1
	    },
	    showWeekNumbers: true,
	    buttonClasses: ['btn-danger'],
	    dateLimit: false
	 },
	 function(start, end) {
	    var base_location = '$target';
	    window.location = base_location + '/' + start.format('$format') + '/' + end.format('$format');
	 }
	);
});
</script>
<div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
	<i class="icon-calendar icon-large"></i>
	<span>$f_start - $f_end</span> 
	<b class="caret"></b>
</div>
EOD;
	return $echo;
	#echo 'aca';
}