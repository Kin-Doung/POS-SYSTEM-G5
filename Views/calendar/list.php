<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<link rel="stylesheet" href="../../views/assets//css/style/calendar.css">
<script src="../../views/assets/js/sciptJS/calendar.js" defer></script>


<div style="margin-left: 220px; margin-top: -30px">
  <?php require_once './views/layouts/nav.php' ?>
</div>
<div class="layout-wrapper ">
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <!-- End Navbar -->

    <body>

      <div id='container'>
        <div id='calendar'></div>
        <div id='eventList'>
          <div class="time-container">
            <button id='todayButton'>Today</button>
            <span id='currentTime'></span>
          </div>
        </div>
      </div>

      <div id='modalOverlay' class='modal-overlay'></div>

      <div id='eventModal' class='modal'>
        <h2>Add Shop Schedule</h2>
        <form id='eventForm'>
          <input type='hidden' id='eventDate'>
          <div id="timeError" style="color: red; font-size: 14px; margin-top: 10px;"></div>

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
          <div class="button1">
            <button type='submit'>Save</button>
            <button type='button' id='closeModal'>Cancel</button>
          </div>

        </form>
      </div>

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
        <span id="closeDetail" class="close-button ">&times;</span>

      </div>

    </body>
  </main>
</div>