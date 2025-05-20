<?php
session_start();
require_once 'config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() == 1) {
                $admin = $stmt->fetch();
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['username'];
                    header("Location: admindash.php");
                    exit();
                } else {
                    $message = "Mot de passe incorrect.";
                }
            } else {
                $message = "Nom d'utilisateur non trouvé.";
            }
        } catch (PDOException $e) {
            $message = "Échec de la connexion: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mes premiers pas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .login-header h1 {
            color: var(--dark-color);
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .login-header p {
            color: var(--secondary-color);
            font-size: 0.9em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 600;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #224abe;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #f8d7da;
            color: var(--danger-color);
            font-size: 0.9em;
            text-align: center;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="images/logo.png" alt="Logo">
            <h1>Administration</h1>
            <p>Connectez-vous à votre espace administrateur</p>
        </div>

        <?php if ($message): ?>
            <div class="message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="back-link">
            <a href="index.html">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</body>

</html>