-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- COLUMN likes, comments, photos -------------------------------------------------
ALTER TABLE {{ t("people_publics") }} ADD COLUMN likes int DEFAULT 0 ;
ALTER TABLE {{ t("people_publics") }} ADD COLUMN comments int DEFAULT 0;
ALTER TABLE {{ t("people_publics") }} ADD COLUMN photos int DEFAULT 0;

ALTER TABLE {{ t("gallery_publics") }} ADD COLUMN likes int DEFAULT 0;
ALTER TABLE {{ t("gallery_publics") }} ADD COLUMN comments int DEFAULT 0;
ALTER TABLE {{ t("gallery_publics") }} ADD COLUMN photos int DEFAULT 0;
