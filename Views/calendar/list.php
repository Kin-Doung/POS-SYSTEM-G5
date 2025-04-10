<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>



<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- End Navbar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
    <link rel="stylesheet" href="../assets/css/style/calendar.css">
    <!-- <style>
      body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #f0f4f8, #e2e8f0);
        margin: 0;
        padding: 30px;
        min-height: 100vh;
      }

      #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        padding: 15px;
      }

      .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
      }

      .modal, .detail-card {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        width: 360px;
        max-width: 90%;
        transition: transform 0.3s ease, opacity 0.3s ease;
      }

      .modal.show, .detail-card.show {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
      }

      .modal h2, .detail-card h2 {
        margin: 0 0 25px;
        font-size: 24px;
        color: #1a202c;
        font-weight: 600;
        text-align: center;
      }

      .modal form div, .detail-card div {
        margin-bottom: 20px;
      }

      .modal label, .detail-card label {
        display: block;
        font-size: 14px;
        color: #4a5568;
        font-weight: 500;
        margin-bottom: 6px;
      }

      .modal input[type="text"], .modal input[type="time"],
      .detail-card input[type="text"], .detail-card input[type="time"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 16px;
        color: #2d3748;
        background: #f7fafc;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
      }

      .modal input[type="text"]:focus, .modal input[type="time"]:focus,
      .detail-card input[type="text"]:focus, .detail-card input[type="time"]:focus {
        border-color: #4299e1;
        box-shadow: 0 0 6px rgba(66, 153, 225, 0.3);
        outline: none;
      }

      .modal button, .detail-card button, #todayButton {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
      }

      .modal button[type="submit"], .detail-card #saveEdit {
        background: linear-gradient(90deg, #4299e1, #2b6cb0);
        color: #fff;
        margin-right: 10px;
      }

      .modal button[type="submit"]:hover, .detail-card #saveEdit:hover {
        background: linear-gradient(90deg, #2b6cb0, #2c5282);
        transform: translateY(-2px);
      }

      .modal button[type="button"], .detail-card #closeDetail {
        background: #edf2f7;
        color: #4a5568;
      }

      .modal button[type="button"]:hover, .detail-card #closeDetail:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
      }

      .detail-card #deleteEvent {
        background: linear-gradient(90deg, #f56565, #c53030);
        color: #fff;
        margin-right: 10px;
      }

      .detail-card #deleteEvent:hover {
        background: linear-gradient(90deg, #c53030, #9b2c2c);
        transform: translateY(-2px);
      }

      #todayButton {
        background: linear-gradient(90deg, #48bb78, #2f855a);
        color: #fff;
        margin: 10px auto;
        display: block;
      }

      #todayButton:hover {
        background: linear-gradient(90deg, #2f855a, #276749);
        transform: translateY(-2px);
      }

      #currentTime {
        font-size: 16px;
        color: #2d3748;
        margin-left: 10px;
        vertical-align: middle;
      }

      .time-container {
        text-align: center;
        margin-top: 10px;
      }

      .fc-daygrid-day-number {
        color: #4299e1;
        font-weight: 500;
      }

      .fc-event {
        background: linear-gradient(90deg, #4299e1, #2b6cb0);
        border: none;
        border-radius: 6px;
        padding: 4px 8px;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .fc-event:hover {
        transform: translateY(-2px);
      }
    </style>
    <script>
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
    </script> -->
<body>
    <div id='calendar'></div>
    
    <div class="time-container">
      <button id='todayButton'>Today</button>
      <span id='currentTime'></span>
    </div>
    
    <div id='modalOverlay' class='modal-overlay'></div>
    
    <!-- Add Event Modal -->
    <div id='eventModal' class='modal'>
      <h2>Add Shop Schedule</h2>
      <form id='eventForm'>
        <input type='hidden' id='eventDate'>
        <div>
          <label>Title:</label>
          <input type='text' id='title' required placeholder="e.g., Buy Supplies">
        </div>
        <div>
          <label>Product Name:</label>
          <input type='text' id='product' required placeholder="e.g., Milk">
        </div>
        <div>
          <label>Time to Buy:</label>
          <input type='time' id='buyTime' required>
        </div>
        <div>
          <label>Alert Time:</label>
          <input type='time' id='alertTime' required>
        </div>
        <button type='submit'>Save</button>
        <button type='button' id='closeModal'>Cancel</button>
      </form>
    </div>

    <!-- Event Detail Card (Editable) -->
    <div id='detailCard' class='detail-card'>
      <h2>Event Details</h2>
      <div>
        <label>Title:</label>
        <input type='text' id='detailTitle'>
      </div>
      <div>
        <label>Product:</label>
        <input type='text' id='detailProduct'>
      </div>
      <div>
        <label>Time to Buy:</label>
        <input type='time' id='detailBuyTime'>
      </div>
      <div>
        <label>Alert Time:</label>
        <input type='time' id='detailAlertTime'>
      </div>
      <button id='saveEdit'>Save</button>
      <button id='deleteEvent'>Delete</button>
      <button id='closeDetail'>Close</button>
    </div>
    <script src="../assets/js/sciptJS/calendar.js" defer ></script>
  </body>