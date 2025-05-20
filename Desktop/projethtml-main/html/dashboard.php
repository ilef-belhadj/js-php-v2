<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
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
    <title>Tableau de Bord Parent - École Maternelle</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
    </style>
</head>

<body>
    <!-- Header Section -->
    <header>
        <h1>Tableau de Bord Parent 🌟</h1>
        <p>Bienvenue dans votre espace personnel</p>
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

    <!-- Main Content -->
    <main>
        <!-- Home Section -->
        <section id="home" class="content-section active">
            <div class="dashboard-grid">
                <!-- Children Information -->
                <section class="dashboard-card">
                    <h2>👶 Informations des enfants</h2>
                    <?php foreach ($children as $child): ?>
                        <div class="child-info">
                            <h3><?php echo htmlspecialchars($child['first_name']); ?></h3>
                            <p>Âge: <?php echo htmlspecialchars($child['age']); ?> ans</p>
                            <p>Niveau: <?php echo htmlspecialchars($child['level']); ?></p>
                            <?php if ($child['allergies']): ?>
                                <p class="health-info">⚠️ Allergies: <?php echo htmlspecialchars($child['allergies_details']); ?></p>
                            <?php endif; ?>
                            <?php if ($child['disability']): ?>
                                <p class="health-info">⭐ Besoins particuliers: <?php echo htmlspecialchars($child['disability_details']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>

                <!-- Contact Information -->
                <section class="dashboard-card">
                    <h2>📞 Informations de contact</h2>
                    <p>Email: <?php echo htmlspecialchars($parent['email']); ?></p>
                    <p>Téléphone: <?php echo htmlspecialchars($parent['phone']); ?></p>
                    <?php if ($parent['phone_secondary']): ?>
                        <p>Téléphone secondaire: <?php echo htmlspecialchars($parent['phone_secondary']); ?></p>
                    <?php endif; ?>
                </section>
            </div>
        </section>

        <!-- Schedule Section -->
        <section id="schedule" class="content-section">
            <div class="dashboard-card">
                <h2>📅 Emploi du temps</h2>
                <!-- Add your schedule content here -->
                <div class="schedule-content">
                    <p>L'emploi du temps sera bientôt disponible.</p>
                </div>
            </div>
        </section>

        <!-- Calendar Section -->
        <section id="calendar" class="content-section">
            <div class="dashboard-card">
                <h2>📆 Calendrier</h2>
                <!-- Add your calendar content here -->
                <div class="calendar-content">
                    <p>Le calendrier sera bientôt disponible.</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Navigation functionality
        document.querySelectorAll('.dashboard-nav a[data-section]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                // Remove active class from all links and sections
                document.querySelectorAll('.dashboard-nav a').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));

                // Add active class to clicked link and corresponding section
                link.classList.add('active');
                const sectionId = link.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
            });
        });
    </script>
</body>

</html>