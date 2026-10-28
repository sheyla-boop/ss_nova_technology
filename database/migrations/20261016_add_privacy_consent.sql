ALTER TABLE users
    ADD COLUMN privacy_policy_version VARCHAR(20) NULL AFTER password_hash,
    ADD COLUMN privacy_accepted_at DATETIME NULL AFTER privacy_policy_version;
