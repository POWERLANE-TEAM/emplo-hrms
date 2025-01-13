import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export function initCalendar(attendanceLogs) {
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
            today: 'Go to Current Month'
        },

        events: attendanceLogs,
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

    document.addEventListener("periodChanged", (event) => {
        const { startDate } = event.detail;

        if (startDate) {
            const parsedDate = new Date(startDate);
            calendar.gotoDate(parsedDate);
        }
    });

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