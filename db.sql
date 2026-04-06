-- ============================================================
--  SalaryPredict — Tables web
-- ============================================================

-- ── Messages de contact ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS contacts (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nom        VARCHAR(120)  NOT NULL,
  email      VARCHAR(180)  NOT NULL,
  sujet      VARCHAR(255)  NOT NULL,
  message    TEXT          NOT NULL,
  lu         TINYINT(1)    DEFAULT 0,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Historique des prédictions du site ───────────────────────
CREATE TABLE IF NOT EXISTS web_predictions (
  id                    INT AUTO_INCREMENT PRIMARY KEY,
  region                VARCHAR(100),
  secteur               VARCHAR(100),
  taille_entreprise     VARCHAR(50),
  seniorite             VARCHAR(50),
  intensite_ia          VARCHAR(10),
  risque_automatisation VARCHAR(10),
  salaire_predit        DECIMAL(12,2),
  created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;