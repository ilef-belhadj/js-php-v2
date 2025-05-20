<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
$pdo = getDBConnection();

// Get parent information
$stmt = $pdo->prepare("SELECT * FROM parents WHERE id = ?");
$stmt->execute([$_SESSION['parent_id']]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

// Get children information
$stmt = $pdo->prepare("SELECT * FROM children WHERE parent_id = ?");
$stmt->execute([$_SESSION['parent_id']]);
$children = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Mes premiers pas</title>
    <link rel="stylesheet" href="../css/emepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-message {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .info-section h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        .info-item {
            margin-bottom: 0.5rem;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .dashboard-nav {
            background-color: #fff;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .dashboard-nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
            padding: 0;
            justify-content: center;
        }

        .dashboard-nav a {
            color: #6b2d68;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .dashboard-nav a:hover {
            color: #f87761;
            background-color: rgba(248, 119, 97, 0.1);
        }

        .dashboard-nav a.active {
            color: #f87761;
            background-color: rgba(248, 119, 97, 0.1);
        }

        .dashboard-nav a i {
            font-size: 1.2rem;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .calendar-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .schedule-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header-logo">
                <img src="../images/logo.png" alt="Logo" class="header-image">
            </div>
            <h1>Mes premiers pas - L'école des futurs élites</h1>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="dashboard-nav">
        <ul>
            <li><a href="#" class="active" data-section="home"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="#" data-section="schedule"><i class="fas fa-calendar-alt"></i> Emploi du temps</a></li>
            <li><a href="#" data-section="calendar"><i class="fas fa-calendar-week"></i> Calendrier</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Home Section -->
        <section id="home" class="content-section active">
            <div class="dashboard">
                <div class="welcome-message">
                    <h2>Bienvenue, <?php echo htmlspecialchars($parent['first_name']); ?>!</h2>
                </div>

                <div class="info-section">
                    <h3>Vos Informations</h3>
                    <div class="info-item">
                        <span class="info-label">Nom:</span>
                        <?php echo htmlspecialchars($parent['first_name'] . ' ' . $parent['last_name']); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <?php echo htmlspecialchars($parent['email']); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone:</span>
                        <?php echo htmlspecialchars($parent['phone'] ?? 'Non fourni'); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Adresse:</span>
                        <?php echo htmlspecialchars($parent['address'] ?? 'Non fournie'); ?>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Informations des enfants</h3>
                    <?php foreach ($children as $child): ?>
                        <div class="info-item">
                            <h4><?php echo htmlspecialchars($child['first_name']); ?></h4>
                            <p>Âge: <?php echo htmlspecialchars($child['age']); ?> ans</p>
                            <p>Niveau: <?php echo htmlspecialchars($child['level']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Schedule Section -->
        <section id="schedule" class="content-section">
            <div class="dashboard">
                <h2>Emploi du temps</h2>
                <div class="schedule-grid">
                    <div class="schedule-card">
                        <h3>Lundi</h3>
                        <p>8h30 - 9h30: Activités créatives</p>
                        <p>10h00 - 11h00: Jeux éducatifs</p>
                        <p>11h30 - 12h30: Déjeuner</p>
                        <p>14h00 - 15h00: Sieste</p>
                        <p>15h30 - 16h30: Activités sportives</p>
                    </div>
                    <div class="schedule-card">
                        <h3>Mardi</h3>
                        <p>8h30 - 9h30: Musique</p>
                        <p>10h00 - 11h00: Langage</p>
                        <p>11h30 - 12h30: Déjeuner</p>
                        <p>14h00 - 15h00: Sieste</p>
                        <p>15h30 - 16h30: Jeux libres</p>
                    </div>
                    <div class="schedule-card">
                        <h3>Mercredi</h3>
                        <p>8h30 - 9h30: Arts plastiques</p>
                        <p>10h00 - 11h00: Éveil corporel</p>
                        <p>11h30 - 12h30: Déjeuner</p>
                        <p>14h00 - 15h00: Sieste</p>
                        <p>15h30 - 16h30: Activités manuelles</p>
                    </div>
                    <div class="schedule-card">
                        <h3>Jeudi</h3>
                        <p>8h30 - 9h30: Éveil musical</p>
                        <p>10h00 - 11h00: Psychomotricité</p>
                        <p>11h30 - 12h30: Déjeuner</p>
                        <p>14h00 - 15h00: Sieste</p>
                        <p>15h30 - 16h30: Jeux collectifs</p>
                    </div>
                    <div class="schedule-card">
                        <h3>Vendredi</h3>
                        <p>8h30 - 9h30: Éveil aux langues</p>
                        <p>10h00 - 11h00: Activités sensorielles</p>
                        <p>11h30 - 12h30: Déjeuner</p>
                        <p>14h00 - 15h00: Sieste</p>
                        <p>15h30 - 16h30: Activités libres</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Calendar Section -->
        <section id="calendar" class="content-section">
            <div class="dashboard">
                <h2>Calendrier des événements</h2>
                <div class="calendar-grid">
                    <div class="calendar-card">
                        <h3>Événements à venir</h3>
                        <ul>
                            <li>15 Mars: Fête du printemps</li>
                            <li>20 Mars: Sortie au parc</li>
                            <li>25 Mars: Spectacle de fin de trimestre</li>
                            <li>30 Mars: Réunion parents-enseignants</li>
                        </ul>
                    </div>
                    <div class="calendar-card">
                        <h3>Vacances scolaires</h3>
                        <ul>
                            <li>1-15 Avril: Vacances de printemps</li>
                            <li>1-15 Juillet: Vacances d'été</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <small>&copy; 2024 Mes premiers pas - Jardin d'enfants. Tous droits réservés.</small>
            </div>
        </div>
    </footer>

    <script>
        // Navigation functionality
        document.querySelectorAll('.dashboard-nav a[data-section]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                showSection(link.getAttribute('data-section'));
            });
        });

        function showSection(sectionId) {
            // Remove active class from all links and sections
            document.querySelectorAll('.dashboard-nav a').forEach(a => a.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));

            // Add active class to clicked link and corresponding section
            document.querySelector(`.dashboard-nav a[data-section="${sectionId}"]`).classList.add('active');
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
</body>

</html>