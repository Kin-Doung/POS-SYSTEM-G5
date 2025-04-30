document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var events = JSON.parse(localStorage.getItem('shopEvents')) || [];

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: events,
    eventDisplay: 'block',
    eventContent: function(arg) {
      return { html: `<div>${arg.event.title}</div>` };
    },
    dateClick: function(info) {
      const clickedDate = new Date(info.dateStr);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (clickedDate < today) {
        alert('You cannot set an event in the past.');
      } else {
        showEventModal(info.dateStr);
      }
    },
    eventClick: function(info) {
      showDetailCard(info.event);
    }
  });
  calendar.render();

  const modal = document.getElementById('eventModal');
  const overlay = document.getElementById('modalOverlay');
  const closeModal = document.getElementById('closeModal');
  const detailCard = document.getElementById('detailCard');
  const closeDetail = document.getElementById('closeDetail');
  const eventList = document.getElementById('eventList');

  let notificationTimeouts = new Map();

  closeModal.onclick = function() {
    hideModal(modal);
  };

  closeDetail.onclick = function() {
    hideModal(detailCard);
  };

  document.getElementById('eventForm').onsubmit = function(e) {
    e.preventDefault();
  
    const title = document.getElementById('title').value;
    const product = document.getElementById('product').value;
    const buyTime = document.getElementById('buyTime').value;
    const alertTime = document.getElementById('alertTime').value;
    const date = document.getElementById('eventDate').value;
    
    const selectedDateTime = new Date(`${date}T${buyTime}`);
    const now = new Date();
  
    const errorDiv = document.getElementById('timeError');
    
    if (selectedDateTime < now) {
      errorDiv.textContent = "You cannot set an event in the past!";
      return;
    } else {
      errorDiv.textContent = "";
    }
  
    const event = {
      id: Date.now().toString(),
      title: title,
      start: `${date}T${buyTime}`,
      extendedProps: {
        product: product,
        buyTime: buyTime,
        alertTime: `${date}T${alertTime}`
      }
    };
  
    const newEvent = calendar.addEvent(event);
    updateLocalStorage();
    updateEventList();
  
    hideModal(modal);
    this.reset();
  
    scheduleNotification(newEvent);
  };

  function showEventModal(date) {
    document.getElementById('eventDate').value = date;
    showModal(modal);
  }

  function showDetailCard(event) {
    document.getElementById('detailTitle').value = event.title;
    document.getElementById('detailProduct').value = event.extendedProps.product;

    const buyTime = new Date(event.start);
    const buyHours = buyTime.getHours().toString().padStart(2, '0');
    const buyMinutes = buyTime.getMinutes().toString().padStart(2, '0');
    document.getElementById('detailBuyTime').value = `${buyHours}:${buyMinutes}`;

    const alertTime = new Date(event.extendedProps.alertTime);
    const alertHours = alertTime.getHours().toString().padStart(2, '0');
    const alertMinutes = alertTime.getMinutes().toString().padStart(2, '0');
    document.getElementById('detailAlertTime').value = `${alertHours}:${alertMinutes}`;

    document.getElementById('saveEdit').onclick = function() {
      const newTitle = document.getElementById('detailTitle').value;
      const newProduct = document.getElementById('detailProduct').value;
      const newBuyTime = document.getElementById('detailBuyTime').value;
      const newAlertTime = document.getElementById('detailAlertTime').value;
      const date = event.start.toISOString().split('T')[0];

      const newBuyDateTime = new Date(`${date}T${newBuyTime}`);
      const newAlertDateTime = new Date(`${date}T${newAlertTime}`);
      const now = new Date();

      if (newBuyDateTime < now || newAlertDateTime < now) {
        alert('You cannot set buy time or alert time in the past!');
        return;
      }

      if (notificationTimeouts.has(event.id)) {
        clearTimeout(notificationTimeouts.get(event.id));
        notificationTimeouts.delete(event.id);
      }

      event.setProp('title', newTitle);
      event.setStart(`${date}T${newBuyTime}`);
      event.setExtendedProp('product', newProduct);
      event.setExtendedProp('buyTime', newBuyTime);
      event.setExtendedProp('alertTime', `${date}T${newAlertTime}`);

      updateLocalStorage();
      updateEventList();
      scheduleNotification(event);
      hideModal(detailCard);
    };

    document.getElementById('deleteEvent').onclick = function() {
      if (confirm('Do you want to delete this event?')) {
        if (notificationTimeouts.has(event.id)) {
          clearTimeout(notificationTimeouts.get(event.id));
          notificationTimeouts.delete(event.id);
        }
        event.remove();
        updateLocalStorage();
        updateEventList();
        hideModal(detailCard);
      }
    };

    showModal(detailCard);
  }

  function showModal(element) {
    element.style.display = 'block';
    overlay.style.display = 'block';
    setTimeout(() => element.classList.add('show'), 10);
  }

  function hideModal(element) {
    element.classList.remove('show');
    setTimeout(() => {
      element.style.display = 'none';
      overlay.style.display = 'none';
    }, 300);
  }

  function updateLocalStorage() {
    const currentEvents = calendar.getEvents().map(event => ({
      id: event.id,
      title: event.title,
      start: event.start.toISOString(),
      extendedProps: event.extendedProps
    }));
    localStorage.setItem('shopEvents', JSON.stringify(currentEvents));
  }

  function updateEventList() {
    const events = calendar.getEvents();
    eventList.innerHTML = '<h2>Scheduled</h2>';
    if (events.length === 0) {
      eventList.innerHTML += '<p>No events scheduled.</p>';
      return;
    }
    events.forEach(event => {
      const eventDiv = document.createElement('div');
      eventDiv.className = 'event-item';
      const eventDate = event.start.toLocaleDateString('en-US', { month: 'numeric', day: 'numeric', year: 'numeric' });
      eventDiv.innerHTML = `
        <div class="event-content">
          <div><strong>Title:</strong> ${event.title}</div>
          <div><strong>Product:</strong> ${event.extendedProps.product}</div>
          <div><strong>Time to Buy:</strong> ${event.start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</div>
          <div><strong>Alert Time:</strong> ${new Date(event.extendedProps.alertTime).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</div>
          <div><strong>Date:</strong> ${eventDate}</div>
        </div>
        <div class="event-menu">
          <i class="fas fa-ellipsis-v menu-icon"></i>
          <div class="dropdown-menu">
            <div class="dropdown-item edit-btn" data-id="${event.id}">
              <i class="fas fa-pen-fancy"></i> Edit
            </div>
            <div class="dropdown-item delete-btn" data-id="${event.id}">
              <i class="fas fa-trash-alt"></i> Delete
            </div>
          </div>
        </div>
        <hr>
      `;
      eventList.appendChild(eventDiv);
    });

    // Add event listeners for dropdown menu
    document.querySelectorAll('.event-menu').forEach(menu => {
      const menuIcon = menu.querySelector('.menu-icon');
      const dropdown = menu.querySelector('.dropdown-menu');

      menuIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelectorAll('.dropdown-menu.show').forEach(openDropdown => {
          if (openDropdown !== dropdown) {
            openDropdown.classList.remove('show');
          }
        });
        dropdown.classList.toggle('show');
      });
    });

    document.addEventListener('click', (e) => {
      if (!e.target.closest('.event-menu')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(dropdown => {
          dropdown.classList.remove('show');
        });
      }
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', function() {
        const eventId = this.getAttribute('data-id');
        const eventToEdit = calendar.getEventById(eventId);
        if (eventToEdit) {
          showDetailCard(eventToEdit);
        }
      });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function() {
        const eventId = this.getAttribute('data-id');
        const eventToDelete = calendar.getEventById(eventId);
        if (confirm('Do you want to delete this event?')) {
          if (notificationTimeouts.has(eventId)) {
            clearTimeout(notificationTimeouts.get(eventId));
            notificationTimeouts.delete(eventId);
          }
          eventToDelete.remove();
          updateLocalStorage();
          updateEventList();
        }
      });
    });
  }

  const TELEGRAM_BOT_TOKEN = '7914523767:AAEJxRARlS6nn4Qggt3lw8pOYWKdjAT3FaY';
  const TELEGRAM_CHAT_ID = '@engly_system_telegram';

  function sendTelegramMessage(message) {
    const url = `https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/sendMessage`;
    const data = {
      chat_id: TELEGRAM_CHAT_ID,
      text: message,
      parse_mode: 'HTML'
    };

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      if (data.ok) {
        console.log('Message sent to Telegram successfully');
      } else {
        console.error('Failed to send message:', data);
      }
    })
    .catch(error => {
      console.error('Error sending Telegram message:', error);
    });
  }

  function scheduleNotification(event) {
    const alertTime = new Date(event.extendedProps.alertTime);
    const now = new Date();
    const timeUntilAlert = alertTime.getTime() - now.getTime();
  
    if (timeUntilAlert > 0) {
      const timeoutId = setTimeout(() => {
        const message = `🔔 <b>Reminder</b>\n\n` +
          `Item: <b>${event.title}</b>\n` +
          `Product: ${event.extendedProps.product}\n` +
          `Time to Buy: ${event.start.toLocaleTimeString()}\n` +
          `\n<b>Thank you</b>🔔`;
        sendTelegramMessage(message);
        notificationTimeouts.delete(event.id);
      }, timeUntilAlert);
      
      notificationTimeouts.set(event.id, timeoutId);
    }
  }

  function autoDeleteExpiredEvents() {
    const now = new Date();
    const events = calendar.getEvents();
    let deleted = false;

    events.forEach(event => {
      const buyTime = new Date(event.start);
      if (buyTime < now) {
        console.log(`Auto-deleting expired event: ${event.title}`);
        if (notificationTimeouts.has(event.id)) {
          clearTimeout(notificationTimeouts.get(event.id));
          notificationTimeouts.delete(event.id);
        }
        event.remove();
        deleted = true;
      }
    });

    if (deleted) {
      updateLocalStorage();
      updateEventList();
    }
  }

  function updateCurrentTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString();
  }

  updateEventList();
  autoDeleteExpiredEvents();

  calendar.getEvents().forEach(event => {
    scheduleNotification(event);
  });

  // Add CSS styles for the event list and dropdown menu
  const style = document.createElement('style');
  style.textContent = `
    #eventList h2 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #343a40;
      text-transform: uppercase;
    }
    .event-item {
      position: relative;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 8px;
      margin-bottom: 10px;
      transition: background 0.3s ease;
    }
    .event-item:hover {
      background: #e9ecef;
    }
    .event-content {
      padding-right: 30px;
    }
    .event-content div {
      margin-bottom: 5px;
      font-size: 14px;
      color: #495057;
    }
    .event-content div strong {
      color: #343a40;
    }
    .event-menu {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    .menu-icon {
      cursor: pointer;
      font-size: 18px;
      color: #6c757d;
      transition: color 0.3s ease;
    }
    .menu-icon:hover {
      color: #343a40;
    }
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 25px;
      right: 0;
      background: #ffffff;
      border: 1px solid #e9ecef;
      border-radius: 5px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      min-width: 120px;
    }
    .dropdown-menu.show {
      display: block;
      margin-left: -10px; /* Adjusted as requested */
    }
    .dropdown-item {
      padding: 8px 15px;
      font-size: 14px;
      color: #343a40;
      cursor: pointer;
      display: flex;
      align-items: center;
      transition: background 0.3s ease, color 0.3s ease;
    }
    .dropdown-item:hover {
      background: #f1f3f5;
      color: #007bff;
    }
    .dropdown-item i {
      margin-right: 8px;
      font-size: 16px;
      background: none !important; /* Remove any background */
      padding: 0; /* Remove any padding that might create a background */
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .dropdown-item:hover i {
      transform: scale(1.2); /* Slight zoom effect on hover */
    }
    .dropdown-item.edit-btn i {
      color: #007bff; /* Blue for edit */
    }
    .dropdown-item.delete-btn i {
      color: #dc3545; /* Red for delete */
    }
    .dropdown-item.edit-btn:hover i {
      color: #0056b3; /* Darker blue on hover */
    }
    .dropdown-item.delete-btn:hover i {
      color: #a71d2a; /* Darker red on hover */
    }
    hr {
      border: 0;
      border-top: 1px solid #e9ecef;
      margin: 10px 0;
    }
  `;
  document.head.appendChild(style);
});