<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<nav>
  <div class="nav-inner">
    <a href="index.php" class="nav-logo">Salary<span class="dot">.</span>Predict</a>
    <div class="nav-links">
      <a href="index.php"       class="<?= $cur==='index.php'?'active':'' ?>">Accueil</a>
      <a href="prediction.php"  class="<?= $cur==='prediction.php'?'active':'' ?>">Prédiction</a>
      <a href="comparaison.php" class="<?= $cur==='comparaison.php'?'active':'' ?>">Comparaison</a>
      <a href="exploration.php" class="<?= $cur==='exploration.php'?'active':'' ?>">Exploration</a>
      <a href="apropos.php"     class="<?= $cur==='apropos.php'?'active':'' ?>">À propos</a>
      <a href="contact.php"     class="<?= $cur==='contact.php'?'active':'' ?>">Contact</a>
    </div>
  </div>
</nav>
