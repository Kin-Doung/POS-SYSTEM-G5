<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .error-page {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
    }

    .error-page img {
        max-width: 400px;
        width: 100%;
        margin-bottom: 30px;
    }

    .error-page h1 {
        font-size: 100px;
        font-weight: bold;
        color: #ff4c4c;
        margin-bottom: 10px;
    }

    .error-page h2 {
        font-size: 32px;
        margin-bottom: 20px;
    }

    .error-page p {
        font-size: 18px;
        color: #555;
        margin-bottom: 30px;
    }

    .error-page a {
        text-decoration: none;
    }
</style>

<div class="error-page">
    <h1>404</h1>
    <h2>Oops! Page not found</h2>
    <p>The page you're looking for doesn't exist or has been moved.</p>
    <a href="/dashboard" class=" btn-primary btn-lg">
        Back to home page
    </a>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

