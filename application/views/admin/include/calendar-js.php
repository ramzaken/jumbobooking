<script src="<?php echo base_url() ?>assets/admin/js/fullcalendar-main.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.js'></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      initialDate: '<?php echo date('Y-m-d') ?>',
      locale: '<?php echo strtolower(lang_short_form()) ?>',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      eventClick: function(info) {
        swal("<?php echo trans('appointments') ?>", info.event.title);
      },
      eventDisplay: 'block',
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: true
      },
      events: [
        <?php foreach ($appointments as $index => $appointment): ?>
          <?php 
            $time = explode("-", $appointment->time, 3); 
            $startTime = html_escape($appointment->date).'T'.$time[0];
            $endTime = isset($time[1]) ? html_escape($appointment->date).'T'.$time[1] : $startTime;
            $isLast = ($index === array_key_last($appointments));
          ?>
          {
            title: '<?php echo html_escape($appointment->service_name . ' - (' . $appointment->time . ') at ' . my_date_show($appointment->date) . ', ' . trans('staff') . ': ' . $appointment->staff_name . ' - ' . trans('customer') . ': ' . $appointment->customer_name) ?>',
            start: '<?php echo $startTime ?>',
            end: '<?php echo $endTime ?>',
            allDay: false
          }<?php if (!$isLast) echo ','; ?>
        <?php endforeach; ?>
      ],
      eventColor: '#3CB371',
      eventDidMount: function(info) {
        // Only apply style in "day" view
        if (calendar.view.type === 'timeGridDay') {
          info.el.style.whiteSpace = 'normal';
          info.el.style.color = '#fff';
          info.el.style.fontSize = '14px';
        }
      }
    });

    calendar.render();
  });
</script>