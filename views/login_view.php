<?php
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<?php require_once './views/layouts/header.php' ?>

<style>
    .container {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
        max-width: 400px;
        margin: 50px auto;
    }

    .logo img {
        width: 80px;
        margin-bottom: 20px;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .subtitle {
        font-size: 14px;
        color: #777;
        margin-bottom: 30px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .input-box {
        position: relative;
    }

    .input-box i {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #888;
    }

    .input-box input {
        width: 100%;
        padding: 10px 10px 10px 35px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition: 0.3s;
    }

    .input-box input:focus {
        border-color: #007bff;
    }

    .error {
        color: #e74c3c;
        font-size: 13px;
        background: #ffecec;
        padding: 8px;
        border-radius: 6px;
    }

    .button input[type="submit"] {
        padding: 10px;
        background: #007bff;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    .button input[type="submit"]:hover {
        background: #0056b3;
    }

    .footer {
        margin-top: 30px;
        font-size: 12px;
        color: #aaa;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

</style>

<div class="container">
    <div class="logo">
        <img src="../views/assets/img/logos/Engly-Logo.png" alt="POS System Logo" onerror="this.src='/assets/img/fallback.png'">
    </div>
    <div class="title">POS System</div>
    <div class="subtitle">Please login to your account</div>

    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div class="input-box">
            <label for="username" class="sr-only">Username</label>
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>

        <div class="input-box">
            <label for="password" class="sr-only">Password</label>
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="button">
            <input type="submit" value="Login">
        </div>
    </form>

    <div class="footer">
        © <?= date('Y') ?> POS System. All rights reserved.
    </div>
</div>
