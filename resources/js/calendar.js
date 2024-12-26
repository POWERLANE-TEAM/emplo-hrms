// Import necessary FullCalendar core and plugins
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        events: [
            {
                title: 'Holiday',
                start: '2024-12-01',
                color: 'green',
                extendedProps: {
                    label: 'Public Holiday'
                }
            },
            {
                title: 'Company Event',
                start: '2024-12-10',
                color: 'blue',
                extendedProps: {
                    label: 'Annual Gathering'
                }
            }
        ],
        eventContent: function(info) {
            // Create a custom event content structure
            let label = info.event.extendedProps.label || '';
            let title = info.event.title;
    
            return {
                html: `<div class="event-label">${label}</div><div class="event-title">${title}</div>`
            };
        }
    });
    
    calendar.render();
});
