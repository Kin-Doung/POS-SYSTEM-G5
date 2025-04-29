
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
      title: title,
      start: `${date}T${buyTime}`,
      extendedProps: {
        product: product,
        buyTime: buyTime,
        alertTime: `${date}T${alertTime}`
      }
    };

    calendar.addEvent(event);
    updateLocalStorage();
    
    hideModal(modal);
    this.reset();
    
    scheduleNotification(event);
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

      event.setProp('title', newTitle);
      event.setStart(`${date}T${newBuyTime}`);
      event.setExtendedProp('product', newProduct);
      event.setExtendedProp('buyTime', newBuyTime);
      event.setExtendedProp('alertTime', `${date}T${newAlertTime}`);

      updateLocalStorage();
      scheduleNotification(event);
      hideModal(detailCard);
    };

    document.getElementById('deleteEvent').onclick = function() {
      if (confirm('Do you want to delete this event?')) {
        event.remove();
        updateLocalStorage();
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
      title: event.title,
      start: event.start.toISOString(),
      extendedProps: event.extendedProps
    }));
    localStorage.setItem('shopEvents', JSON.stringify(currentEvents));
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
    const timeUntilAlert = alertTime - now;

    if (timeUntilAlert > 0) {
      setTimeout(() => {
        const message = `Reminder: <b>${event.title}</b>\nProduct: ${event.extendedProps.product}\nTime to Buy: ${event.start.toLocaleTimeString()}\nAlert Time: ${new Date(event.extendedProps.alertTime).toLocaleTimeString()}`;
        sendTelegramMessage(message);
      }, timeUntilAlert);
    }
  }

  // Display current time near Today button
  function updateCurrentTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString();
  }

  // Update time every second
  setInterval(updateCurrentTime, 1000);
  updateCurrentTime(); // Initial call

  // Today button functionality
  document.getElementById('todayButton').onclick = function() {
    calendar.today(); // Go to today's date in FullCalendar
  };

  // Schedule notifications for existing events
  events.forEach(event => {
    const alertTime = new Date(event.extendedProps.alertTime);
    const now = new Date();
    if (alertTime > now) {
      scheduleNotification(event);
    }
  });
});