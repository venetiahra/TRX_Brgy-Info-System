USE barangay_db;

ALTER TABLE barangay_officials
    ADD COLUMN captain_bio TEXT NULL,
    ADD COLUMN secretary_bio TEXT NULL,
    ADD COLUMN treasurer_bio TEXT NULL,
    ADD COLUMN captain_photo VARCHAR(255) NULL,
    ADD COLUMN secretary_photo VARCHAR(255) NULL,
    ADD COLUMN treasurer_photo VARCHAR(255) NULL;