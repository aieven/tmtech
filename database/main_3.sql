-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- INSERT GALLERY PUBLICS -----------------------------------------------
CREATE OR REPLACE FUNCTION insert_gallery_public(
  p_gal_id int,
  p_username text,
  p_profile_picture text
) RETURNS int AS
$$
DECLARE
  p_public_id int;
BEGIN
    UPDATE {{ t("gallery_publics") }}
      SET deleted = FALSE,
          gallery_id = p_gal_id
      WHERE username = p_username
        AND deleted IS TRUE
      RETURNING public_id INTO p_public_id;

    IF found THEN
      RETURN p_public_id;
    END IF;

    BEGIN
        INSERT INTO {{ t("gallery_publics") }} ( gallery_id, username, profile_picture )
          VALUES ( p_gal_id, p_username, p_profile_picture )
          RETURNING public_id INTO p_public_id;
        RETURN p_public_id;

    EXCEPTION WHEN unique_violation THEN
      RETURN NULL;

    END;
END;
$$
LANGUAGE plpgsql;

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- ALTER GALLERY PUBLICS -----------------------------------------------
CREATE UNIQUE INDEX gallery_publics_unique ON {{ t("gallery_publics") }} ( username );
