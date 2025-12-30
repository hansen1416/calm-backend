-- Users (app-level user record; Cognito is the IdP)
CREATE TABLE IF NOT EXISTS  users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cognito_sub VARCHAR(64) NOT NULL,
  email VARCHAR(255) NOT NULL,
  name VARCHAR(255) NULL,
  plan VARCHAR(32) NOT NULL DEFAULT 'free',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_cognito_sub (cognito_sub),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Email campaigns (graph-based campaign builder; stores nodes/edges/viewport as JSON)
CREATE TABLE IF NOT EXISTS  email_campaigns (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,

  name VARCHAR(255) NOT NULL,
  status VARCHAR(32) NOT NULL DEFAULT 'draft',   -- draft|active|archived (extend later)

  graph_json JSON NOT NULL,                      -- { nodes, edges, viewport }

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id)
--   INDEX idx_email_campaigns_user_id (user_id),

--   CONSTRAINT fk_email_campaigns_user
--     FOREIGN KEY (user_id) REFERENCES users(id)
--     ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

