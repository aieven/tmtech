-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- COLUMN followers -------------------------------------------------
ALTER TABLE {{ t("people_publics") }} RENAME COLUMN followers_count TO followers;
ALTER TABLE {{ t("brands_publics") }} ADD COLUMN followers int DEFAULT 0;
