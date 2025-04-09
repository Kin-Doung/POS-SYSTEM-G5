
const daysEl = document.getElementById('days');
const monthYearEl = document.getElementById('monthYear');
const selectedDateEl = document.getElementById('displayDate');
const plansListEl = document.getElementById('plansList');
const titleInput = document.getElementById('title');
const setTimeInput = document.getElementById('setTime');
const setTimePeriod = document.getElementById('setTimePeriod');
const alertTimeInput = document.getElementById('alertTime');
const alertTimePeriod = document.getElementById('alertTimePeriod');
const descriptionInput = document.getElementById('description');
const addPlanButton = document.getElementById('addPlanButton');
const formTitle = document.getElementById('formTitle');
const formContainer = document.getElementById('formContainer');
const planModal = document.getElementById('planModal');
const modalTitle = document.getElementById('modalTitle');
const modalSetTime = document.getElementById('modalSetTime');
const modalAlertTime = document.getElementById('modalAlertTime');
const modalDescription = document.getElementById('modalDescription');
const modalDeleteBtn = document.getElementById('modalDeleteBtn');
const allPlansModal = document.getElementById('allPlansModal');
const allPlansModalTitle = document.getElementById('allPlansModalTitle');
const allPlansList = document.getElementById('allPlansList');
let selectedDate;
let editIndex = null;
let currentModalDate;
let currentModalIndex;

let schedules = JSON.parse(localStorage.getItem('schedules')) || {};

function formatDateToDDMMYY(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

function formatTime(time, period) {
    if (!time || !period) return 'Not set';
    const [hour, minute] = time.split(':');
    const hourNum = parseInt(hour);
    const formattedHour = hourNum % 12 || 12;
    return `time = ${formattedHour}:${minute} ${period}`;
}

function convertTo24Hour(time, period) {
    if (!time || !period) return '';
    const [hour, minute] = time.split(':');
    let hourNum = parseInt(hour);
    if (period === 'pm' && hourNum !== 12) {
        hourNum += 12;
    } else if (period === 'am' && hourNum === 12) {
        hourNum = 0;
    }
    return `${String(hourNum).padStart(2, '0')}:${minute}`;
}

function parseTimeForEdit(time) {
    if (!time) return { time: '', period: 'am' };
    const [hour, minute] = time.split(':');
    const hourNum = parseInt(hour);
    const period = hourNum >= 12 ? 'pm' : 'am';
    const formattedHour = hourNum % 12 || 12;
    return { time: `${String(formattedHour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`, period };
}

function renderCalendar() {
    const now = new Date('2025-04-06');
    const year = now.getFullYear();
    const month = now.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    monthYearEl.textContent = `${now.toLocaleString('default', { month: 'long' })} ${year}`;
    daysEl.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        daysEl.innerHTML += '<div></div>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const dateStr = date.toISOString().split('T')[0];
        const isPast = date < new Date('2025-04-06').setHours(0, 0, 0, 0);
        const isScheduled = schedules[dateStr] && schedules[dateStr].length > 0;

        const dayEl = document.createElement('div');
        dayEl.className = `day ${isPast ? 'past' : ''} ${isScheduled ? 'scheduled' : ''}`;
        dayEl.innerHTML = `${day}`;
        
        if (isScheduled) {
            const plans = schedules[dateStr];
            const firstPlan = plans[0];
            let info = `<div class="day-info">
                <span class="plan-title" onclick="showPlanDetails('${dateStr}', 0)">${firstPlan.title || 'Untitled'}</span><br>
                ${firstPlan.setTime ? firstPlan.setTime : 'No time set'}
            </div>`;

            if (plans.length > 1) {
                info += `<div class="show-more" onclick="showAllPlans('${dateStr}')">Show More (${plans.length} plans)</div>`;
            }

            dayEl.innerHTML += info;
        }

        if (!isPast) {
            dayEl.onclick = (e) => {
                if (!e.target.classList.contains('plan-title') && !e.target.classList.contains('show-more')) {
                    openModal(dateStr);
                }
            };
        }

        daysEl.appendChild(dayEl);
    }

    cleanupPastSchedules();
}

function showAllPlans(date) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot view plans for past dates.');
        return;
    }

    currentModalDate = date;
    allPlansModalTitle.textContent = `Plans for ${formatDateToDDMMYY(dateObj)}`;
    renderAllPlansList(date);
    allPlansModal.style.display = 'block';
}

function renderAllPlansList(date) {
    allPlansList.innerHTML = '';
    const plans = schedules[date] || [];
    plans.forEach((plan, index) => {
        const setTimeDetails = parseTimeForEdit(plan.setTime);
        const alertTimeDetails = parseTimeForEdit(plan.alertTime);
        const planItem = document.createElement('div');
        planItem.className = 'plan-item';
        planItem.innerHTML = `
            <h4>${plan.title || 'Untitled'}</h4>
            <p><strong>Set Time:</strong> ${formatTime(setTimeDetails.time, setTimeDetails.period)}</p>
            <p><strong>Alert Time:</strong> ${formatTime(alertTimeDetails.time, alertTimeDetails.period)}</p>
            <p><strong>Description:</strong> ${plan.description || 'None'}</p>
            <div class="actions">
                <button class="edit-btn" onclick="editPlanFromModal('${date}', ${index})">Edit</button>
                <button class="delete-btn" onclick="deletePlanFromModal('${date}', ${index})">Delete</button>
            </div>
        `;
        allPlansList.appendChild(planItem);
    });
}

function editPlanFromModal(date, index) {
    closeAllPlansModal();
    editPlan(date, index);
}

function deletePlanFromModal(date, index) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot delete plans for past dates.');
        return;
    }

    if (schedules[date]) {
        schedules[date].splice(index, 1);
        if (schedules[date].length === 0) {
            delete schedules[date];
        }
        try {
            localStorage.setItem('schedules', JSON.stringify(schedules));
        } catch (e) {
            console.error('Error saving to localStorage:', e);
        }
        renderAllPlansList(date);
        renderCalendar();
        if (selectedDate === date) {
            renderPlans();
        }
        if (!schedules[date] || schedules[date].length === 0) {
            closeAllPlansModal();
        }
    }
}

function closeAllPlansModal() {
    allPlansModal.style.display = 'none';
    currentModalDate = null;
}

function showPlanDetails(date, index) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot view details for past dates.');
        return;
    }

    const plan = schedules[date][index];
    const setTimeDetails = parseTimeForEdit(plan.setTime);
    const alertTimeDetails = parseTimeForEdit(plan.alertTime);

    modalTitle.textContent = plan.title || 'Untitled';
    modalSetTime.textContent = formatTime(setTimeDetails.time, setTimeDetails.period);
    modalAlertTime.textContent = formatTime(alertTimeDetails.time, alertTimeDetails.period);
    modalDescription.textContent = plan.description || 'None';

    currentModalDate = date;
    currentModalIndex = index;
    modalDeleteBtn.onclick = () => deleteFromModal(date, index);

    planModal.style.display = 'block';
}

function deleteFromModal(date, index) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot delete plans for past dates.');
        return;
    }

    if (schedules[date]) {
        schedules[date].splice(index, 1);
        if (schedules[date].length === 0) {
            delete schedules[date];
        }
        try {
            localStorage.setItem('schedules', JSON.stringify(schedules));
        } catch (e) {
            console.error('Error saving to localStorage:', e);
        }
        closePlanModal();
        renderPlans();
        renderCalendar();
    }
}

function closePlanModal() {
    planModal.style.display = 'none';
    currentModalDate = null;
    currentModalIndex = null;
}

function openModal(date) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot add or edit plans for past dates.');
        return;
    }

    selectedDate = date;
    const dateObjDisplay = new Date(date);
    selectedDateEl.textContent = formatDateToDDMMYY(dateObjDisplay);
    renderPlans();
}

function renderPlans() {
    plansListEl.innerHTML = '';

    const plans = schedules[selectedDate] || [];
    plans.forEach((plan, index) => {
        const setTimeDetails = parseTimeForEdit(plan.setTime);
        const alertTimeDetails = parseTimeForEdit(plan.alertTime);
        const card = document.createElement('div');
        card.className = 'plan-card';
        card.innerHTML = `
            <div class="plan-header">${plan.title || 'Untitled'}</div>
            <div class="plan-details">
                <p>${formatTime(setTimeDetails.time, setTimeDetails.period)}</p>
                <p>${formatTime(alertTimeDetails.time, alertTimeDetails.period)}</p>
                <p><strong>Description:</strong> ${plan.description || 'None'}</p>
            </div>
            <button class="edit-btn" onclick="editPlan('${selectedDate}', ${index})">Edit</button>
            <button class="delete-btn" onclick="deletePlan('${selectedDate}', ${index})">Delete</button>
        `;
        plansListEl.appendChild(card);
    });

    if (plans.length > 0) {
        addPlanButton.classList.remove('hidden');
        formTitle.classList.add('hidden');
        formContainer.classList.add('hidden');
    } else {
        showForm();
    }
}

function editPlan(date, index) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot edit plans for past dates.');
        return;
    }

    const plan = schedules[date][index];
    editIndex = index;
    formTitle.textContent = 'Edit Plan';

    titleInput.value = plan.title || '';
    const setTimeDetails = parseTimeForEdit(plan.setTime);
    setTimeInput.value = setTimeDetails.time;
    setTimePeriod.value = setTimeDetails.period;
    const alertTimeDetails = parseTimeForEdit(plan.alertTime);
    alertTimeInput.value = alertTimeDetails.time;
    alertTimePeriod.value = alertTimeDetails.period;
    descriptionInput.value = plan.description || '';

    showForm();
}

function deletePlan(date, index) {
    const dateObj = new Date(date);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot delete plans for past dates.');
        return;
    }

    if (schedules[date]) {
        schedules[date].splice(index, 1);
        if (schedules[date].length === 0) {
            delete schedules[date];
        }
        try {
            localStorage.setItem('schedules', JSON.stringify(schedules));
        } catch (e) {
            console.error('Error saving to localStorage:', e);
        }
        renderPlans();
        renderCalendar();
    }
}

function closeModal() {
    clearForm();
    editIndex = null;
    formTitle.textContent = 'Add New Plan';
    formTitle.classList.add('hidden');
    formContainer.classList.add('hidden');
    const plans = schedules[selectedDate] || [];
    if (plans.length > 0) {
        addPlanButton.classList.remove('hidden');
    }
}

function clearForm() {
    titleInput.value = '';
    setTimeInput.value = '';
    setTimePeriod.value = 'am';
    alertTimeInput.value = '';
    alertTimePeriod.value = 'am';
    descriptionInput.value = '';
}

function saveSchedule() {
    const title = titleInput.value.trim();
    const setTime = setTimeInput.value.trim();
    const setTimePeriodValue = setTimePeriod.value;
    const alertTime = alertTimeInput.value.trim();
    const alertTimePeriodValue = alertTimePeriod.value;
    const description = descriptionInput.value.trim();

    if (!title || !setTime || !alertTime) {
        alert('Please fill in all required fields: Title, Set Time, and Alert Time.');
        return;
    }

    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (!timeRegex.test(setTime) || !timeRegex.test(alertTime)) {
        alert('Please enter valid times in the format HH:MM (e.g., 03:00).');
        return;
    }

    const dateObj = new Date(selectedDate);
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    if (dateObj < now) {
        alert('Cannot add or edit plans for past dates.');
        return;
    }

    const setTime24 = convertTo24Hour(setTime, setTimePeriodValue);
    const alertTime24 = convertTo24Hour(alertTime, alertTimePeriodValue);

    const newPlan = {
        title: title,
        setTime: setTime24,
        alertTime: alertTime24,
        description: description || ''
    };

    if (!schedules[selectedDate]) {
        schedules[selectedDate] = [];
    }

    if (editIndex !== null) {
        schedules[selectedDate][editIndex] = newPlan;
        editIndex = null;
        formTitle.textContent = 'Add New Plan';
    } else {
        schedules[selectedDate].push(newPlan);
    }

    try {
        localStorage.setItem('schedules', JSON.stringify(schedules));
    } catch (e) {
        console.error('Error saving to localStorage:', e);
    }

    cleanupPastSchedules();
    renderPlans();
    renderCalendar();
}

function cleanupPastSchedules() {
    const now = new Date('2025-04-06').setHours(0, 0, 0, 0);
    Object.keys(schedules).forEach(date => {
        if (new Date(date) < now) {
            delete schedules[date];
        }
    });
    try {
        localStorage.setItem('schedules', JSON.stringify(schedules));
    } catch (e) {
        console.error('Error saving to localStorage during cleanup:', e);
    }
}


function showForm() {
    formTitle.classList.remove('hidden');
    formContainer.classList.remove('hidden');
    addPlanButton.classList.add('hidden');
    if (editIndex === null) {
        clearForm();
    }
}
function toggleOptions() {
    const options = document.getElementById('options');
    options.style.display = options.style.display === 'none' ? 'block' : 'none';
}

window.onclick = function(event) {
    if (event.target == planModal) {
        closePlanModal();
    }
    if (event.target == allPlansModal) {
        closeAllPlansModal();
    }
};

renderCalendar();
