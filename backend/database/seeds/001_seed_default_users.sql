INSERT INTO users (username, password_hash)
VALUES
    ('mateusadm', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e'),
    ('gabriel.silva', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e')
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    updated_at = CURRENT_TIMESTAMP;
