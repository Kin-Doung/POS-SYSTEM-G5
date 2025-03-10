<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login account</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style/from.css">
</head>
<body>

<div class="container">
    <input type="checkbox" id="flip">
    <!-- Cover Image -->
    <div class="cover">
        <div class="front">
            <img src="/assets/images/image.png" alt="Cover Image">
        </div>
        <div class="back">
            <img src="/assets/images/image.png" alt="Cover Image">
        </div>
    </div>

    <!-- Forms for Login and Signup -->
    <div class="forms">
        <!-- Login Form -->
        <div class="form-content">
            <div class="login-form">
                <div class="title">Login</div>
                
                <!-- Error message div will be injected here -->
                <div id="error-message"></div>  
                <form method="POST" action="/login">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        
                        <div class="text"><a href="#">Forgot password?</a></div>
                        <div class="button input-box">
                            <input type="submit" value="Login">
                        </div>
                        <div class="text sign-up-text">Don't have an account? <label for="flip">Sign up now</label></div>
                    </div>
                </form>
            </div>

            <!-- Signup Form -->
            <div class="signup-form">
                <div class="title">Sign Up</div>
                <form method="POST" action="/login">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" value="Submit">
                        </div>
                        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<script>
// This script is necessary to display the error message dynamically
document.addEventListener("DOMContentLoaded", function() {
    // If there's an error message set from the backend, display it
    <?php if (isset($_SESSION['error_message'])): ?>
        document.getElementById('error-message').innerHTML = 
            '<div class="error-message"><?php echo $_SESSION['error_message']; ?></div>';
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
});
</script>

