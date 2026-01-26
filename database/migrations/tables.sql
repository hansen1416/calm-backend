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

-- Contacts (belongs to a user)
CREATE TABLE IF NOT EXISTS contacts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  email VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  -- FKs / indexes
  CONSTRAINT fk_contacts_user_id
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  -- Prevent duplicate contacts per user
  UNIQUE KEY uq_contacts_user_email (user_id, email),

  KEY idx_contacts_user_id (user_id),
  KEY idx_contacts_email (email),
  KEY idx_contacts_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tags (user-defined)
CREATE TABLE IF NOT EXISTS tags (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(64) NOT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  CONSTRAINT fk_tags_user_id
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  UNIQUE KEY uq_tags_user_name (user_id, name),
  KEY idx_tags_user_id (user_id),
  KEY idx_tags_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Contact <-> Tag (many-to-many), with user_id to enforce ownership consistency
CREATE TABLE IF NOT EXISTS contact_tags (
  user_id BIGINT UNSIGNED NOT NULL,
  contact_id BIGINT UNSIGNED NOT NULL,
  tag_id BIGINT UNSIGNED NOT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (contact_id, tag_id),

  KEY idx_contact_tags_user_id (user_id),
  KEY idx_contact_tags_tag_id (tag_id),

  -- Contact must belong to user_id
  CONSTRAINT fk_contact_tags_contact
    FOREIGN KEY (contact_id) REFERENCES contacts(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  -- Tag must belong to user_id
  CONSTRAINT fk_contact_tags_tag
    FOREIGN KEY (tag_id) REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  -- Enforce that both the contact and tag are owned by the same user
  CONSTRAINT fk_contact_tags_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Email campaigns (graph-based campaign builder; stores nodes/edges/viewport as JSON)
CREATE TABLE IF NOT EXISTS  email_campaigns (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,

  name VARCHAR(255) NOT NULL,
  status VARCHAR(32) NOT NULL DEFAULT 'draft',   -- draft|active|archived (extend later)

  mode ENUM('include','exclude') NOT NULL DEFAULT 'include',
  kind ENUM('contact','group') NULL,
  entity_id BIGINT UNSIGNED NULL,

  graph_json JSON NOT NULL,                      -- { nodes, edges, viewport }

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id)
--   INDEX idx_email_campaigns_user_id (user_id),

--   CONSTRAINT fk_email_campaigns_user
--     FOREIGN KEY (user_id) REFERENCES users(id)
--     ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Seed: one user for local/dev
INSERT INTO users (cognito_sub, email, name)
VALUES ('00000000-0000-0000-0000-000000000001', 'user1@example.com', 'User1')
ON DUPLICATE KEY UPDATE
  email = VALUES(email),
  name = VALUES(name),
  updated_at = CURRENT_TIMESTAMP;
