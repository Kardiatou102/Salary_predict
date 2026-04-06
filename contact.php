<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact — SalaryPredict</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes/nav.php'; require_once 'config.php';

$success = false;
$error   = null;
$f       = ['nom'=>'','email'=>'','sujet'=>'','message'=>''];

// Créer la table si elle n'existe pas encore
if($pdo) {
  try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
      id         INT AUTO_INCREMENT PRIMARY KEY,
      nom        VARCHAR(120)  NOT NULL,
      email      VARCHAR(180)  NOT NULL,
      sujet      VARCHAR(255)  NOT NULL,
      message    TEXT          NOT NULL,
      lu         TINYINT(1)    DEFAULT 0,
      created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  } catch(Exception $e) {}
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $f = [
    'nom'     => trim($_POST['nom']     ?? ''),
    'email'   => trim($_POST['email']   ?? ''),
    'sujet'   => trim($_POST['sujet']   ?? ''),
    'message' => trim($_POST['message'] ?? ''),
  ];

  // Validation simple — pas de longueur minimum
  if(!$f['nom'] || !$f['email'] || !$f['sujet'] || !$f['message']) {
    $error = "Veuillez remplir tous les champs.";
  } elseif(!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) {
    $error = "L'adresse email n'est pas valide.";
  } elseif(!$pdo) {
    $error = "Base de données non disponible. Vérifiez que MAMP est démarré.";
  } else {
    try {
      $pdo->prepare(
        "INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)"
      )->execute([$f['nom'], $f['email'], $f['sujet'], $f['message']]);
      $success = true;
    } catch(Exception $e) {
      // Message d'erreur utile pour le débogage
      $error = "Impossible d'enregistrer le message. Détail : " . $e->getMessage();
    }
  }
}
?>

<div class="container page-content">
  <div class="section-header reveal">
    <h1>Contact</h1>
    <p>Une question, une suggestion ou un bug ? Écrivez-nous.</p>
  </div>

  <div style="display:grid;grid-template-columns:1fr 280px;gap:28px;align-items:start;">

    <div class="card reveal reveal-delay-1">
      <?php if($success): ?>
        <div style="text-align:center;padding:48px 0;">
          <div style="font-size:3rem;margin-bottom:16px;">✅</div>
          <h2 style="font-family:'Instrument Serif',serif;font-size:1.8rem;font-weight:400;margin-bottom:10px;">Message envoyé !</h2>
          <p style="color:var(--text-muted);">Merci, nous lirons votre message avec attention.</p>
          <a href="contact.php" class="btn btn-secondary" style="margin-top:24px;">Envoyer un autre message</a>
        </div>
      <?php else: ?>
        <div class="card-title">Formulaire de contact</div>
        <div class="card-sub">Tous les champs sont obligatoires</div>

        <form method="POST">
          <div class="form-grid">

            <div class="form-group">
              <label>👤 Nom</label>
              <input type="text" name="nom"
                value="<?= htmlspecialchars($f['nom']) ?>"
                placeholder="Votre nom" required>
            </div>

            <div class="form-group">
              <label>📧 Email</label>
              <input type="email" name="email"
                value="<?= htmlspecialchars($f['email']) ?>"
                placeholder="votre@email.com" required>
            </div>

            <div class="form-group full">
              <label>📌 Sujet</label>
              <select name="sujet" required>
                <option value="">— Choisir un sujet —</option>
                <?php foreach(['Question sur l\'outil','Bug / Erreur','Suggestion','Autre'] as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>"
                  <?= $f['sujet']===$s ? 'selected' : '' ?>>
                  <?= htmlspecialchars($s) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group full">
              <label>💬 Message</label>
              <textarea name="message"
                placeholder="Décrivez votre demande…"
                required><?= htmlspecialchars($f['message']) ?></textarea>
            </div>

            <div class="form-group full">
              <button type="submit" class="btn btn-primary"
                style="width:100%;justify-content:center;padding:14px;">
                📩 Envoyer
              </button>
            </div>

          </div>
        </form>

        <?php if($error): ?>
          <div class="alert alert-error" style="margin-top:14px;">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- SIDEBAR -->
    <div style="display:flex;flex-direction:column;gap:16px;">
      <div class="card reveal reveal-delay-2">
        <div class="card-title">ℹ️ À savoir</div>
        <div style="display:flex;flex-direction:column;gap:12px;margin-top:10px;font-size:0.86rem;color:var(--text-muted);">
          <div style="display:flex;gap:10px;"><span>🎓</span><span>Projet académique L3 MIASHS</span></div>
          <div style="display:flex;gap:10px;"><span>🔬</span><span>Code source disponible sur demande</span></div>
          <div style="display:flex;gap:10px;"><span>⚡</span><span>Nous répondons dans les meilleurs délais</span></div>
        </div>
      </div>
    </div>

  </div>
</div>

<footer>
  <strong>SalaryPredict</strong> &nbsp;·&nbsp;
  L3 MIASHS · Université de Montpellier Paul-Valéry &nbsp;·&nbsp;
  <?= date('Y') ?>
</footer>

<script>
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }});
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>
</body>
</html>
