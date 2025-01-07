import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
    initCalendar();
});

export function initCalendar() {
    const calendarEl = document.getElementById("calendar");

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        contentHeight: "auto",
        aspectRatio: 1.35,
        windowResize: function (arg) {
            calendar.updateSize();
        },
        buttonText: {
            today: 'Go to Current Month'  // Change the text of the "Today" button
        },

        /*
        BACK-END REPLACE: Replace the event array section with event data fetched from the database.

        NOTE: Assign appropriate classNames based on the event type:
        - Regular holidays: Use 'bg-info' for styling.
        - Special non-working holidays: Use 'bg-warning' for styling.
        - Company events: Use 'bg-primary' for styling.

        */

        events: [
            // December 2024 - Regular Holidays
            {
                title: "Bonifacio Day",
                start: "2024-12-01",
                classNames: ["bg-info"],
            },

            {
                title: "Christmas Day",
                start: "2024-12-25",
                classNames: ["bg-info"],
            },
            {
                title: "Rizal Day",
                start: "2024-12-30",
                classNames: ["bg-info"],
            },
            {
                title: "New Year’s Eve",
                start: "2024-12-31",
                classNames: ["bg-info"],
            },

            // December 2024 - Special Non-working
            {
                title: "Feast of the Immaculate Conception",
                start: "2024-12-08",
                classNames: ["bg-warning"],
            },
            {
                title: "Christmas Eve",
                start: "2024-12-24",
                classNames: ["bg-warning"],
            },
            {
                title: "Special Holiday",
                start: "2024-12-26",
                classNames: ["bg-warning"],
            },

            // December 2024 - Company Events
            {
                title: "Year-End Party",
                start: "2024-12-15",
                classNames: ["bg-primary"],
            },
            {
                title: "Annual Planning",
                start: "2024-12-20",
                classNames: ["bg-primary"],
            },

            // January 2025 - Regular Holidays
            {
                title: "New Year’s Day",
                start: "2025-01-01",
                classNames: ["bg-info"],
            },
            {
                title: "Feast of the Black Nazarene",
                start: "2025-01-09",
                classNames: ["bg-info"],
            },
            {
                title: "Chinese New Year",
                start: "2025-01-29",
                classNames: ["bg-info"],
            },
            {
                title: "Special Regular Holiday",
                start: "2025-01-30",
                classNames: ["bg-info"],
            },

            // January 2025 - Special Non-working
            {
                title: "Special Non-working Day",
                start: "2025-01-02",
                classNames: ["bg-warning"],
            },
            {
                title: "Special Holiday",
                start: "2025-01-15",
                classNames: ["bg-warning"],
            },
            {
                title: "Additional Non-working Day",
                start: "2025-01-22",
                classNames: ["bg-warning"],
            },

            // January 2025 - Company Events
            {
                title: "Kick-off Meeting",
                start: "2025-01-10",
                classNames: ["bg-primary"],
            },
            {
                title: "Regular Holiday With Very Long Name",
                start: "2025-01-25",
                classNames: ["bg-primary"],
            },
            {
                title: "Team Building Activity",
                start: "2025-01-25",
                classNames: ["bg-warning"],
            },
            {
                title: "Time Out Company",
                start: "2025-01-25",
                classNames: ["bg-info"],
            },
        ],

        expandRows: true,
        dayMaxEvents: 2,
        eventContent: function (info) {
            let label = info.event.extendedProps.label || "";
            let title = info.event.title;

            return {
                html: `<div class="event-label">${label}</div><div class="event-title">${title}</div>`,
            };
        },
    });
    calendar.render();

    // Resizing calendar in opening/closing sidebar

    const resizeCalendar = () => {
        console.log('Resizing calendar...');
        if (calendar) {
            calendar.updateSize();
        } else {
            console.log('Calendar instance not found');
        }
    };

    const mainSideBar = document.querySelector('sidebar.main-menu');
    const mainMenuToggles = document.querySelectorAll('button.main-menu');

    mainMenuToggles.forEach((btn) => {
        btn.onclick = function () {
            console.log('Sidebar toggle clicked');
            setTimeout(() => {
                console.log('Calling updateSize after sidebar toggle');
                resizeCalendar();
            }, 250); 
        };
    });

    if (mainSideBar) {
        mainSideBar.addEventListener('transitionend', () => {
            console.log('Sidebar transition ended');
            resizeCalendar();
        });
    } else {
        console.log('Sidebar element not found');
    }
}

window.initCalendar = initCalendar;