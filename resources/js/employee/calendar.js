import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
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
        - Worked Regular Schedule: Use 'bg-primary' for styling.
        - Worked Overtime: Use 'bg-info' for styling.
        - Leave: Use 'bg-teal' for styling.
        - Absent: Use 'bg-danger' for styling.
        - Tardy: Use 'bg-teal' for styling.

        For the Leave, use start and end util. See the example below.

        */

        events: [
            // December 2024 - Worked Regular Schedule
            {
                title: "Worked Regular Schedule",
                start: "2024-12-01",
                classNames: ["bg-primary"], // Worked Regular Schedule
            },
            {
                title: "Worked Regular Schedule",
                start: "2024-12-25",
                classNames: ["bg-primary"], // Worked Regular Schedule
            },
            {
                title: "Worked Regular Schedule",
                start: "2024-12-30",
                classNames: ["bg-primary"], // Worked Regular Schedule
            },
            {
                title: "Worked Regular Schedule",
                start: "2024-12-31",
                classNames: ["bg-primary"], // Worked Regular Schedule
            },
        
            // December 2024 - Worked Overtime
            {
                title: "Worked Overtime",
                start: "2024-12-08",
                classNames: ["bg-info"], // Worked Overtime
            },
            {
                title: "Worked Overtime",
                start: "2024-12-24",
                classNames: ["bg-info"], // Worked Overtime
            },
        
            // December 2024 - Leave
            {
                title: "Leave",
                start: "2024-12-15",
                end: "2024-12-18",
                classNames: ["bg-teal"], // Leave
            },
            {
                title: "Leave",
                start: "2024-12-20",
                classNames: ["bg-teal"], // Leave
            },
        
            // December 2024 - Absent
            {
                title: "Absent",
                start: "2024-12-10",
                classNames: ["bg-danger"], // Absent
            },
            {
                title: "Absent",
                start: "2024-12-22",
                classNames: ["bg-danger"], // Absent
            },

            {
                title: "Tardy",
                start: "2024-12-04",
                classNames: ["bg-warning"], // Absent
            },

            {
                title: "Tardy",
                start: "2024-01-06",
                classNames: ["bg-warning"], // Absent
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
});
