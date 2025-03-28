<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - EventPass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body{
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://source.unsplash.com/random/1920x1080/?concert,stage') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }
        :root {
            --primary-color: #0d6efd;
            --accent-color: #fd7e14;
        }
        .admin-container {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .admin-sidebar {
            background: #212529;
            min-height: 100vh;
            padding: 0;
        }
        .sidebar-link {
            color: rgba(255,255,255,0.7);
            transition: all 0.3s;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container login-container" id="loginForm">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="bi bi-shield-lock"></i> Вход для администратора</h4>
            </div>
            <div class="card-body">
                <form action = "./server/auth.php" method = "POST" id="adminLoginForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Логин</label>
                        <input type="text" name = "username" class="form-control border border-dark" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" name = "password" class="form-control border border-dark" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Войти
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
