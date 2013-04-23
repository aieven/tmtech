-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- INSERT PUBLICS -----------------------------------------------
CREATE OR REPLACE FUNCTION insert_people_public(
  p_cat_id int,
  p_username text,
  p_profile_picture text,
  p_subcat_id int DEFAULT NULL
) RETURNS int AS
$$
DECLARE
  p_public_id int;
BEGIN
    UPDATE {{ t("people_publics") }}
      SET deleted = FALSE,
          cat_id = p_cat_id,
          subcat_id = p_subcat_id
      WHERE username = p_username
        AND deleted IS TRUE
      RETURNING public_id INTO p_public_id;

    IF found THEN
      RETURN p_public_id;
    END IF;

    BEGIN
        INSERT INTO {{ t("people_publics") }} ( subcat_id, cat_id, username, profile_picture )
          VALUES ( p_subcat_id, p_cat_id, p_username, p_profile_picture )
          RETURNING public_id INTO p_public_id;
        RETURN p_public_id;

    EXCEPTION WHEN unique_violation THEN
      RETURN NULL;

    END;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION insert_brands_public(
  p_cat_id int,
  p_username text,
  p_profile_picture text,
  p_subcat_id int DEFAULT NULL
) RETURNS int AS
$$
DECLARE
  p_public_id int;
BEGIN
    UPDATE {{ t("brands_publics") }}
      SET deleted = FALSE,
          cat_id = p_cat_id,
          subcat_id = p_subcat_id
      WHERE username = p_username
        AND deleted IS TRUE
      RETURNING public_id INTO p_public_id;

    IF found THEN
      RETURN p_public_id;
    END IF;

    BEGIN
        INSERT INTO {{ t("brands_publics") }} ( subcat_id, cat_id, username, profile_picture )
          VALUES ( p_subcat_id, p_cat_id, p_username, p_profile_picture )
          RETURNING public_id INTO p_public_id;
        RETURN p_public_id;

    EXCEPTION WHEN unique_violation THEN
      RETURN NULL;

    END;
END;
$$
LANGUAGE plpgsql;







