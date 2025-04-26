
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
      showEventModal(info.dateStr);
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
    document.getElementById('detailBuyTime').value = event.extendedProps.buyTime;
    document.getElementById('detailAlertTime').value = event.extendedProps.alertTime.split('T')[1];

    document.getElementById('saveEdit').onclick = function() {
      const newTitle = document.getElementById('detailTitle').value;
      const newProduct = document.getElementById('detailProduct').value;
      const newBuyTime = document.getElementById('detailBuyTime').value;
      const newAlertTime = document.getElementById('detailAlertTime').value;
      const date = event.start.toISOString().split('T')[0];

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
    eventList.innerHTML = '<h2>Scheduled Events</h2>';
    if (events.length === 0) {
      eventList.innerHTML += '<p>No events scheduled.</p>';
      return;
    }
    events.forEach(event => {
      const eventDiv = document.createElement('div');
      eventDiv.className = 'event-item';
      const eventDate = event.start.toLocaleDateString();
      eventDiv.innerHTML = `
        <div><strong>Title:</strong> ${event.title}</div>
        <div><strong>Product:</strong> ${event.extendedProps.product}</div>
        <div><strong>Time to Buy:</strong> ${event.start.toLocaleTimeString()}</div>
        <div><strong>Alert Time:</strong> ${new Date(event.extendedProps.alertTime).toLocaleTimeString()}</div>
        <div><strong>Date:</strong> ${eventDate}</div>
        <button class="delete-btn" data-id="${event.id}">Delete</button>
        <hr>
      `;
      eventList.appendChild(eventDiv);
    });

    // Add event listeners to delete buttons
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
        const message = `Reminder: <b>${event.title}</b>\nProduct: ${event.extendedProps.product}\nTime to Buy: ${event.start.toLocaleTimeString()}\nAlert Time: ${alertTime.toLocaleTimeString()}`;
        sendTelegramMessage(message);
        notificationTimeouts.delete(event.id);
      }, timeUntilAlert);
      
      notificationTimeouts.set(event.id, timeoutId);
    }
  }

  calendar.getEvents().forEach(event => {
    scheduleNotification(event);
  });

  function updateCurrentTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString();
  }

  setInterval(updateCurrentTime, 1000);
  updateCurrentTime();

  document.getElementById('todayButton').onclick = function() {
    calendar.today();
  };

  updateEventList();
});
