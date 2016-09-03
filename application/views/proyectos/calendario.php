<script src='<?= assets_url("bower_components/fullcalendar/fullcalendar.js")?>'></script>
<script> 
	$(function() { 
		var calendar = $('#calendar').fullCalendar({
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			buttonText: {
			    prev:     '&lsaquo;', // <
			    next:     '&rsaquo;', // >
			    prevYear: '&laquo;',  // <<
			    nextYear: '&raquo;',  // >>
			    today:    'hoy',
			    month:    'mes',
			    week:     'semana',
			    day:      'día'
			},
			dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
			eventSources: [
	        // your event source
	        {
	            url: '<?=base_url('/proyectos/eventos_ajax/' . $proyecto_id)?>',
	            error: function() {
	                alert('Hubo un error trayendo los eventos!');
	            },
	            color: 'yellow',   // a non-ajax option
	            textColor: 'black' // a non-ajax option
	        }]

		});
	});
</script> 
<section>
	<div id='calendar'></div> 
	<button onclick='window.print()'>Imprimir</button>
</section>
