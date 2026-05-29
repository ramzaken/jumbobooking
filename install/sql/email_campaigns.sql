-- Email Campaigns feature schema
-- Safe to run on a fresh install. For an existing install whose
-- email_campaign_recipients table predates auto-login, run the ALTER
-- block at the bottom of this file instead of the CREATE.

CREATE TABLE IF NOT EXISTS `email_campaigns` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `body` longtext,
  `recipient_type` varchar(20) DEFAULT 'all',
  `plan_id` int DEFAULT NULL,
  `manual_emails` text,
  `schedule_type` varchar(20) DEFAULT 'none',
  `scheduled_at` datetime DEFAULT NULL,
  `recur_days` varchar(100) DEFAULT NULL,
  `recur_time` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'draft',
  `sent_count` int DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_campaign_recipients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `campaign_id` int NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `login_token` varchar(64) DEFAULT NULL,
  `login_expires` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `click_count` int DEFAULT 0,
  `visited_at` datetime DEFAULT NULL,
  `registered_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `token` (`token`),
  KEY `login_token` (`login_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_suppression` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `reason` varchar(20) DEFAULT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Upgrade an existing email_campaign_recipients table for auto-login.
-- Run only if the table already existed without these columns:
--
-- ALTER TABLE `email_campaign_recipients`
--   ADD COLUMN `login_token` varchar(64) DEFAULT NULL AFTER `token`,
--   ADD COLUMN `login_expires` datetime DEFAULT NULL AFTER `login_token`,
--   ADD KEY `login_token` (`login_token`);
