<nav class="navbar">
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
        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h3>Notifications</h3>
                <a href="#" id="close-notifications"><i class="fas fa-times"></i></a>
            </div>
            <div class="notification-item">
                <img src="/views/assets/images/Cake mixer.png" alt="User Avatar">
                <div class="notification-content">
                    <p><strong>Frankie Sullivan</strong> commented on your post</p>
                    <p>"This is looking great!"</p>
                    <small>Friday 2:20pm, Sep 20, 2024</small>
                </div>
            </div>
            <div class="notification-item">
                <img src="/views/assets/images/Adapter.png" alt="User Avatar">
                <div class="notification-content">
                    <p><strong>Amélie Laurent</strong> followed you</p>
                    <small>Friday 10:04am, Sep 20, 2024</small>
                </div>
            </div>
            <div class="notification-item">
                <img src="/views/assets/images/Cocktail machine.png" alt="User Avatar">
                <div class="notification-content">
                    <p><strong>Mikah DiStefano</strong> uploaded 2 attachments</p>

                    <p>Prototype recording_01.mp4 (14 MB)</p>
                    <small>Thursday 2:20p, Sep 20, 2024</small>
                </div>
            </div>
            <div class="notification-footer">
                <a href="#">Mark all as read</a>
                <a href="/notifications">View all notifications</a>
            </div>
        </div>
    </div>
    <div class="profile" id="profile">
        <div class="profile-section" id="profile-section">

            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span id="profile-name" style="color: darkslategray;">Eng Ly</span>
                <span class="store-name" id="store-name">Owner Store</span>
            </div>
            <ul class="menu" id="menu">
                <li><a href="/settings" class="item"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="/language" class="item"><i class="fas fa-language"></i> Language</a></li>
                <li><a href="/logout" class="item"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

            </ul>
            <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
            <link rel="stylesheet" href="../../views/assets/css/settings/setting.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        </div>

    </div>
</nav>

<!-- Include your existing setting.js file -->
<script src="../../views/assets/js/setting.js"></script>
<!-- Add JavaScript for the notification dropdown -->
<script>
    // Toggle Notification Dropdown
    const notificationIcon = document.getElementById('notification-icon');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const closeNotifications = document.getElementById('close-notifications');

    // When the notification icon is clicked, show or hide the dropdown
    notificationIcon.addEventListener('click', (event) => {
        event.stopPropagation(); // Prevent the click from closing the dropdown immediately
        notificationDropdown.classList.toggle('show');
    });

    closeNotifications.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent any default link behavior
        notificationDropdown.classList.remove('show');
    });

    // Close the notification dropdown when clicking outside
    window.addEventListener('click', (event) => {
        if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.remove('show');
        }
    });
</script>