<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>


<link rel="stylesheet" href="../assets/css/style/calendar.css">
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                    

                <header>
                    <h1>Product Purchase Calendar</h1>
                </header>

                <div class="container">
                    <div class="calendar">
                        <h2 id="monthYear"></h2>
                        <div class="days" id="days"></div>
                    </div>

                    <div id="plansDisplay">
                        <h3>Plans for <span id="displayDate"></span></h3>
                        <div id="plansList"></div>
                        <h4 id="formTitle" class="hidden">Add New Plan</h4>
                        <div id="formContainer" class="hidden">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" id="title" placeholder="Purchase Title" required>
                            </div>
                            <div class="form-group">
                                <label>Set Time:</label>
                                <input type="time" id="setTime" required>
                                 <select id="setTimePeriod">
                            <option value="am">AM</option>
                            <option value="pm">PM</option>
                        </select>
                            </div>
                            <div class="form-group">
                                <label>Alert Time:</label>
                                <input type="time" id="alertTime" required>
                                 <select id="alertTimePeriod">
                            <option value="am">AM</option>
                            <option value="pm">PM</option>
                        </select>
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea id="description" placeholder="Enter description"></textarea>
                            </div>
                            <button onclick="saveSchedule()">Save</button>
                            <button onclick="closeModal()">Cancel</button>
                        </div>
                        <button onclick="showForm()" id="addPlanButton" class="hidden">Add Next Plan</button>
                    </div>
                </div>
               

                    <div id="allPlansModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeAllPlansModal()">×</span>
                            <h3 id="allPlansModalTitle"></h3>
                            <div id="allPlansList" class="all-plans-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../../views/assets/js/demo/chart-area-demo.js"></script>