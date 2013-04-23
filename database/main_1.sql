-----------------------------------------------------------------------------------------------------------------------
------------------------------------------------- PUBLISH_SNAPSHOT ----------------------------------------------------

CREATE OR REPLACE FUNCTION publish_snapshot( s_id integer ) RETURNS integer AS
$$
BEGIN
    BEGIN
      UPDATE {{ t("snapshots") }} SET published = 0;
      UPDATE {{ t("snapshots")}} SET published = 1 WHERE snapshot_id = s_id;
      RETURN s_id;
    END;
END;
$$
LANGUAGE plpgsql;