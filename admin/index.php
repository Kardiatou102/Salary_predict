<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — SalaryPredict</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
// ── Authentification basique ─────────────────────────────────
// Change ces identifiants avant de mettre en ligne !
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'salary2024!');

session_start();
$error='';
if(isset($_POST['login'])){
  if($_POST['u']===ADMIN_USER && $_POST['p']===ADMIN_PASS){
    $_SESSION['admin']=true;
  } else {
    $error="Identifiants incorrects.";
  }
}
if(isset($_GET['logout'])){ session_destroy(); header('Location: index.php'); exit; }

if(!isset($_SESSION['admin'])):
?>
<!-- LOGIN -->
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg)">
  <div class="card" style="width:340px">
    <div style="text-align:center;margin-bottom:24px">
      <div style="font-size:2rem;margin-bottom:8px">🔐</div>
      <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800">Espace Admin</h1>
      <p style="color:var(--text-muted);font-size:0.85rem">SalaryPredict</p>
    </div>
    <form method="POST" style="display:flex;flex-direction:column;gap:14px">
      <div class="form-group">
        <label>Identifiant</label>
        <input type="text" name="u" placeholder="admin" required autocomplete="username">
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="p" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <?php if($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>
      <button type="submit" name="login" class="btn btn-primary" style="justify-content:center;padding:13px">Se connecter</button>
    </form>
    <p style="text-align:center;margin-top:16px"><a href="../index.php" style="font-size:0.83rem;color:var(--text-muted)">← Retour au site</a></p>
  </div>
</div>
<?php else: ?>
<!-- DASHBOARD ADMIN -->
<?php
require_once '../config.php';

// Suppression d'un message
if(isset($_GET['del']) && $pdo){
  $pdo->prepare("DELETE FROM contacts WHERE id=?")->execute([(int)$_GET['del']]);
  header('Location: index.php'); exit;
}

// Marquer comme lu
if(isset($_GET['read']) && $pdo){
  $pdo->prepare("UPDATE contacts SET lu=1 WHERE id=?")->execute([(int)$_GET['read']]);
}

$messages=[];$stats_pred=null;
if($pdo){
  try{
    // Ajouter colonne lu si elle n'existe pas
    try{ $pdo->exec("ALTER TABLE contacts ADD COLUMN lu TINYINT(1) DEFAULT 0"); }catch(Exception $e){}

    $messages=$pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
    $stats_pred=$pdo->query("
      SELECT COUNT(*) nb, ROUND(AVG(salaire_predit)) avg_s, MAX(salaire_predit) max_s
      FROM web_predictions
    ")->fetch();
    $unread=(int)$pdo->query("SELECT COUNT(*) FROM contacts WHERE lu=0")->fetchColumn();
  }catch(Exception $e){}
}
$unread=$unread??0;
?>

<nav style="background:var(--text);position:sticky;top:0;z-index:200">
  <div class="nav-inner">
    <span style="font-family:'Syne',sans-serif;font-weight:800;color:#fff;font-size:1.1rem">🔐 Admin Panel</span>
    <div style="display:flex;align-items:center;gap:12px">
      <a href="../index.php" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.85rem">← Site public</a>
      <a href="?logout" class="btn btn-secondary btn-sm">Déconnexion</a>
    </div>
  </div>
</nav>

<div class="container page-content">
  <div class="section-header fade-up">
    <h1>Dashboard Admin</h1>
    <p>Vue d'ensemble des messages reçus et des statistiques de prédiction.</p>
  </div>

  <!-- MINI STATS -->
  <div class="stats-grid fade-up" style="grid-template-columns:repeat(4,1fr);margin-bottom:36px">
    <div class="stat-item">
      <div class="stat-val"><?= count($messages) ?></div>
      <div class="stat-label">Messages reçus</div>
    </div>
    <div class="stat-item">
      <div class="stat-val" style="color:<?= $unread>0?'var(--gold)':'var(--accent)' ?>"><?= $unread ?></div>
      <div class="stat-label">Non lus</div>
    </div>
    <div class="stat-item">
      <div class="stat-val"><?= $stats_pred['nb']??0 ?></div>
      <div class="stat-label">Prédictions effectuées</div>
    </div>
    <div class="stat-item">
      <div class="stat-val">$<?= number_format($stats_pred['avg_s']??0,0,',',' ') ?></div>
      <div class="stat-label">Salaire moyen prédit</div>
    </div>
  </div>

  <!-- MESSAGES -->
  <div class="admin-wrap fade-up fade-up-1">
    <div class="admin-header">
      <h2>📩 Messages de contact</h2>
      <?php if($unread>0): ?><span style="background:var(--gold);color:#fff;padding:3px 10px;border-radius:100px;font-size:0.78rem;font-weight:700"><?= $unread ?> non lu<?= $unread>1?'s':'' ?></span><?php endif; ?>
    </div>

    <?php if(empty($messages)): ?>
      <div style="padding:48px;text-align:center;color:var(--text-muted)">Aucun message reçu pour l'instant.</div>
    <?php else: ?>
      <?php foreach($messages as $m): ?>
      <div style="padding:20px 24px;border-bottom:1px solid var(--border);background:<?= !$m['lu']?'var(--accent-light)':'var(--surface)' ?>">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap">
          <div style="flex:1">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
              <?php if(!$m['lu']): ?><span class="tag tag-blue">Nouveau</span><?php endif; ?>
              <strong style="font-size:0.95rem"><?= htmlspecialchars($m['nom']) ?></strong>
              <span style="color:var(--text-muted);font-size:0.82rem"><?= htmlspecialchars($m['email']) ?></span>
            </div>
            <div style="font-weight:600;font-size:0.88rem;margin-bottom:6px;color:var(--text-muted)">
              Sujet : <?= htmlspecialchars($m['sujet']) ?>
            </div>
            <p style="font-size:0.9rem;color:var(--text);line-height:1.6"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;flex-shrink:0">
            <span style="color:var(--text-muted);font-size:0.78rem;white-space:nowrap"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></span>
            <div style="display:flex;gap:6px">
              <?php if(!$m['lu']): ?>
                <a href="?read=<?= $m['id'] ?>" class="btn btn-secondary btn-sm">✓ Lu</a>
              <?php endif; ?>
              <a href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=Re: <?= htmlspecialchars($m['sujet']) ?>" class="btn btn-primary btn-sm">↩ Répondre</a>
              <a href="?del=<?= $m['id'] ?>" class="btn btn-sm" style="background:var(--red-light);color:var(--red);border:1px solid #f5c0c0"
                 onclick="return confirm('Supprimer ce message ?')">🗑</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- LIEN HISTORIQUE PRÉDICTIONS -->
  <div style="margin-top:24px;text-align:right">
    <a href="../historique.php" class="btn btn-secondary">📋 Voir l'historique des prédictions →</a>
  </div>
</div>

<footer style="margin-top:60px">
  <strong>SalaryPredict Admin</strong> &nbsp;·&nbsp; <?= date('Y') ?>
</footer>
<?php endif; ?>
</body>
</html>
