function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    alert("Logging out securely... Returning home.");
    localStorage.clear();
    window.location.href = "logout.php";
}

const monthsList = [
    "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE",
    "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
];

let currentMonthIndex = 5; // Default view anchor initialized to June to match current workspace dates
let currentYear = 2026;
let chosenDateNumber = "";

document.addEventListener("DOMContentLoaded", () => {
    const savedName = localStorage.getItem("currentUserName");
    if (savedName) {
        document.getElementById("dynamic-username").textContent = savedName;
        document.getElementById("backup_user_name").value = savedName;
    }

    document.getElementById("prev-month-btn").addEventListener("click", goToPreviousMonth);
    document.getElementById("next-month-btn").addEventListener("click", goToNextMonth);

    renderCalendar();
});

function renderCalendar() {
    const gridContainer = document.getElementById("calendar-days-container");
    const titleLabel = document.getElementById("calendar-month-year-title");
    if (!gridContainer || !titleLabel) return;

    gridContainer.innerHTML = "";
    titleLabel.textContent = `${monthsList[currentMonthIndex]} ${currentYear}`;

    const headers = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
    headers.forEach(h => {
        const hDiv = document.createElement("div");
        hDiv.className = "day-header";
        if (h === "SUN" || h === "SAT") hDiv.classList.add("weekend");
        hDiv.textContent = h;
        gridContainer.appendChild(hDiv);
    });

    const firstDayIndex = new Date(currentYear, currentMonthIndex, 1).getDay();
    const totalDaysInMonth = new Date(currentYear, currentMonthIndex + 1, 0).getDate();

    for (let i = 0; i < firstDayIndex; i++) {
        const emptyCell = document.createElement("div");
        emptyCell.className = "day-cell empty";
        gridContainer.appendChild(emptyCell);
    }

    for (let day = 1; day <= totalDaysInMonth; day++) {
        const dateCell = document.createElement("div");
        dateCell.className = "day-cell";
        dateCell.textContent = day;

        const specificDayOfWeek = new Date(currentYear, currentMonthIndex, day).getDay();
        const isWeekend = (specificDayOfWeek === 0 || specificDayOfWeek === 6);

        if (isWeekend) {
            dateCell.classList.add("weekend");
        } else {
            dateCell.addEventListener("click", () => {
                document.querySelector(".day-cell.active")?.classList.remove("active");
                dateCell.classList.add("active");
                chosenDateNumber = day;
                
                const fullDateString = `${monthsList[currentMonthIndex]} ${day}, ${currentYear}`;
                document.getElementById("hidden_booking_date").value = fullDateString;
            });

            if (String(chosenDateNumber) === String(day)) {
                dateCell.classList.add("active");
            }
        }
        gridContainer.appendChild(dateCell);
    }
}

function goToPreviousMonth() {
    if (currentMonthIndex === 0) {
        currentMonthIndex = 11;
        currentYear--;
    } else {
        currentMonthIndex--;
    }
    chosenDateNumber = "";
    renderCalendar();
}

function goToNextMonth() {
    if (currentMonthIndex === 11) {
        currentMonthIndex = 0;
        currentYear++;
    } else {
        currentMonthIndex++;
    }
    chosenDateNumber = "";
    renderCalendar();
}

function selectTimeSlot(buttonElement) {
    document.querySelector(".time-slot-btn.active")?.classList.remove("active");
    buttonElement.classList.add("active");
    
    const timeValue = buttonElement.textContent.trim();
    document.getElementById("hidden_booking_time").value = timeValue;
}