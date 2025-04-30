<?php require_once './views/layouts/side.php' ?>

<style>
    .nav-container{
        margin-left: 250px;   
    }
    .navbar{
        box-shadow: none;
    }
</style>

<div class="nav-container">
<?php require_once './views/layouts/nav.php' ?>
</div>




<!-- log out form -->
<div class="auth-container" style="background:none;">
    <div class="logout-card">
        <div class="profile-pic">
            <img src="../../views/assets/images/image.png" alt="">
        </div>
        <div class="eng-ly">Engly shop</div>
        <div class="user-info">
            <p>Username: 
                <span id="username" class="blur-text">Engly</span> 
                <i class="toggle-icon" onclick="toggleVisibility('username', this)">👁️</i>
            </p>
        </div>
        <div class="user-pass">
            <p>Password: 
                <span id="password" class="blur-text">123</span> 
                <i class="toggle-icon" onclick="toggleVisibility('password', this)">👁️</i>
            </p>
        </div>
        <div class="user-email">
            <p>Email: engly@gmail.com</p>
        </div>
        <div class="logout-btn">
            <a href="/">Logout</a>
        </div>
    </div>
