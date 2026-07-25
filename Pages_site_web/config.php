<?php
define('DB_HOST',    'sql107.infinityfree.com');
define('DB_PORT',    3306);
define('DB_NAME',    'if0_41587919_db_job');
define('DB_USER',    'if0_41587919');
define('DB_PASS',    '3qoL6aVlg92k');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $pdo = null;
    die("Erreur de connexion à la base : " . $e->getMessage());
}

// ── URL de l'API Flask (PythonAnywhere) ─────────────────────
// Après déploiement sur PythonAnywhere, remplacer par :
// define('API_URL', 'https://TONPSEUDO.pythonanywhere.com');
define('API_URL', 'http://127.0.0.1:5000');  // local pour les tests

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Ne pas exposer les détails en production
    error_log("DB Error: " . $e->getMessage());
    $pdo = null;
}

// ── Helper : appel API Flask ─────────────────────────────────
function callPredictAPI(array $data): array {
    $url = API_URL . '/predict';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($err) return ['error' => "Impossible de joindre l'API : $err"];
    $decoded = json_decode($resp, true);
    return $decoded ?? ['error' => 'Réponse invalide de l\'API'];
}

// ── Helper : catégories depuis l'API ────────────────────────
function getCategories(): array {
    // Cache local pour ne pas appeler l'API à chaque requête
    $cache = __DIR__ . '/cache/categories.json';
    if (file_exists($cache) && (time() - filemtime($cache)) < 3600) {
        return json_decode(file_get_contents($cache), true) ?? [];
    }
    $ch = curl_init(API_URL . '/categories');
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
    $resp = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($resp, true) ?? [];
    if ($data) {
        @mkdir(__DIR__ . '/cache', 0755, true);
        file_put_contents($cache, json_encode($data));
    }
    return $data;
}
