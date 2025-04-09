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
                <nav class="navbar ml-4 mb-5">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="icons">
                        <i class="fas fa-globe icon-btn"></i>
                        <div class="icon-btn" id="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notification-count">8</span>
                        </div>
                    </div>
                    <div class="profile" id="profile">
                        <img src="../../views/assets/images/image.png" alt="User">
                        <div class="profile-info">
                            <span id="profile-name">Eng Ly</span>
                            <span class="store-name" id="store-name">Owner Store</span>
                        </div>
                        <ul class="menu" id="menu">
                            <li><a href="/settings" class="item">Account</a></li>
                            <li><a href="/settings" class="item">Setting</a></li>
                            <li><a href="/logout" class="item">Logout</a></li>
                        </ul>
                        <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
                        <script src="../../views/assets/js/setting.js"></script>
                    </div>
                </nav>

                <header>
                    <h1>Product Purchase Calendarr</h1>
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