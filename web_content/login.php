<?php
    include "../src/cdn/cdn_links.php";
    include "../render/connection.php"
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login | Secure Access Portal</title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" href="../src/image/logo/varay_logo.png" type="image/png">
        <link rel="stylesheet" href="../src/style/login_style.css">
        
    </head>
    <body>

        <div class="card login-card p-4">
            <div class="card-body">
                <div class="text-center">
                    <img src="../src/image/logo/varay_logo.png" alt="Logo" class="logo-img">
                    <h4 class="fw-bold text-dark mb-1 text-uppercase" style="letter-spacing: 2px;">Sign In</h4>
                    <p class="text-muted small mb-4">Authorized Access Only</p>
                </div>

                <form method="POST" action="../src/php_script/check_login_credentials.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase text-muted">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user-check"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Enter identity" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-uppercase text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-dark btn-login text-white" type="submit">
                            SECURE LOGIN <i class="fa-solid fa-chevron-right ms-2" style="font-size: 0.8rem;"></i>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4 pt-3 border-top login-footer">
                    &copy; 2026 STOCK FOCUS SYSTEM<br>
                    <span class="fw-bold text-dark">CORE INVENTORY MANAGEMENT</span>
                </div>
            </div>
        </div>
    </body>
</html>