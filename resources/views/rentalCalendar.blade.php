<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        .fc,
        .fc-header-toolbar .fc-button,
        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion,
        .fc-col-header-cell-cushion,
        .fc-timegrid-axis-cushion,
        .fc-timegrid-slot-label-cushion,
        .fc-list-event-time, 
        .fc-list-event-title,
        .fc-daygrid-week-number {
            color: white !important;
        }
    </style>
</head>

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($rentalEvents); ?>,
                eventClick: function(info) {
                    if (info.event.url) {
                        window.open(info.event.url);
                        info.jsEvent.preventDefault();
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                locale: "<?php echo __('messages.language') ?>",
                firstDay: 1,
                weekNumbers: true,
                eventColor: '#3788d8',
                eventTextColor: '#fff',
            });
            calendar.render();
        });
    </script>
</x-app-layout>