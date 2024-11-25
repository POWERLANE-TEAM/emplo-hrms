// BACK-END Replace: Replace with the holidays.

const holidays = {
    2024: [
        { name: 'New Year\'s Day', date: 'January 1, 2024' },
        { name: 'Independence Day', date: 'July 4, 2024' },
        { name: 'Christmas Day', date: 'December 25, 2024' },
        { name: 'New Year\'s Day', date: 'January 1, 2024' },
        { name: 'Independence Day', date: 'July 4, 2024' },
        { name: 'Christmas Day', date: 'December 25, 2024' },
        { name: 'Christmas Day', date: 'December 25, 2024' },
        { name: 'New Year\'s Day', date: 'January 1, 2024' },
        { name: 'Independence Day', date: 'July 4, 2024' },
        { name: 'Christmas Day', date: 'December 25, 2024' }
    ],
    2025: [
        { name: 'New Year\'s Day', date: 'January 1, 2025' },
        { name: 'Labor Day', date: 'May 1, 2025' },
        { name: 'Thanksgiving Day', date: 'November 27, 2025' },
        { name: 'New Year\'s Day', date: 'January 1, 2025' },
        { name: 'Labor Day', date: 'May 1, 2025' },
        { name: 'Thanksgiving Day', date: 'November 27, 2025' }
    ],
    2026: [
        { name: 'New Year\'s Day', date: 'January 1, 2026' },
        { name: 'Valentine\'s Day', date: 'February 14, 2026' },
        { name: 'Halloween', date: 'October 31, 2026' },
        { name: 'New Year\'s Day', date: 'January 1, 2026' },
        { name: 'Valentine\'s Day', date: 'February 14, 2026' },
        { name: 'Halloween', date: 'October 31, 2026' }
    ]
};

// Initialize the current year
let currentYear = 2024;

// Function to update the holidays displayed for the current year
export function updateHolidays() {
    const holidaysList = document.getElementById('holidaysList');
    const currentYearDisplay = document.getElementById('currentYear');

    // Clear the list before adding new holidays
    holidaysList.innerHTML = '';

    // Update the year display
    currentYearDisplay.textContent = currentYear;

    // Get the holidays for the current year and display them in a table
    if (holidays[currentYear]) {
        holidays[currentYear].forEach(holiday => {
            const row = document.createElement('tr');
            
            const nameCell = document.createElement('td');
            nameCell.textContent = holiday.name;

            const dateCell = document.createElement('td');
            dateCell.textContent = holiday.date;

            row.appendChild(nameCell);
            row.appendChild(dateCell);
            
            holidaysList.appendChild(row);
        });
    } else {
        holidaysList.innerHTML = '<tr><td colspan="2">No holidays available for this year.</td></tr>';
    }
}

window.updateHolidays = updateHolidays;

// Event listeners for the previous and next year buttons
document.getElementById('prevYearBtn').addEventListener('click', () => {
    if (holidays[currentYear - 1]) {
        currentYear--;
        updateHolidays();
    }
});

document.getElementById('nextYearBtn').addEventListener('click', () => {
    if (holidays[currentYear + 1]) {
        currentYear++;
        updateHolidays();
    }
});

// Initialize the display with the holidays for the current year
updateHolidays();