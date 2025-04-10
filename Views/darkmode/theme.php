<?php
session_start();

// Check if theme is set in session or POST
if (isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
}

// Get current theme (default to light if not set)
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CODE WITH HOSSEIN</title>
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-text: #333333;
            --toggle-bg: #e0e0e0;
            --toggle-ball: #ffffff;
        }

        /* Dark mode variables */
        .dark {
            --primary-bg: #1a1a1a;
            --primary-text: #ffffff;
            --toggle-bg: #333333;
        }

        body {
            background: var(--primary-bg);
            color: var(--primary-text);
            transition: all 0.3s ease;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        /* Enhanced Toggle Styles */
        .theme-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .theme-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--toggle-bg);
            transition: 0.4s;
            border-radius: 30px;
            border: 2px solid #666;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background: var(--toggle-ball);
            transition: 0.4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        input:checked + .slider {
            background: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(28px);
        }

        .icons {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 8px;
            box-sizing: border-box;
            pointer-events: none;
        }

        .bx {
            font-size: 18px;
            color: #666;
        }
    </style>
    <!-- BoxIcons v2.1.2 -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="<?php echo $theme; ?>">
    <form method="POST" id="themeForm">
        <label class="theme-toggle">
            <input type="checkbox" id="themeToggle" 
                <?php echo $theme === 'dark' ? 'checked' : ''; ?>>
            <span class="slider">
                <span class="icons">
                    <i class='bx bxs-sun'></i>
                    <i class='bx bx-moon'></i>
                </span>
            </span>
        </label>
    </form>


    <script>
        const toggle = document.getElementById('themeToggle');
        const body = document.body;
        const form = document.getElementById('themeForm');

        toggle.addEventListener('change', function() {
            const newTheme = this.checked ? 'dark' : 'light';
            body.className = newTheme;
            
            // Submit theme preference to PHP
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'theme=' + newTheme
            });
        });
    </script>
</body>
</html>