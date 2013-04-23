-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- ADMINS -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("admins") }} (
  admin_id SERIAL PRIMARY KEY NOT NULL,
  email text NOT NULL,
  password char(40) NOT NULL,
  privileges int[] NOT NULL,
  UNIQUE (email)
);

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- BOTS -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("bots") }} (
  instagram_id bigint PRIMARY KEY NOT NULL,
  bot_name text NOT NULL DEFAULT ''::text,
  instagram_token text NOT NULL,
  request_available integer NOT NULL DEFAULT 5000,
  types integer[] DEFAULT ARRAY[1],
  busy integer NOT NULL DEFAULT '0',
  last_update integer NOT NULL DEFAULT '0',
  UNIQUE ( instagram_id )
);
CREATE INDEX bots_for_browsing ON {{ t("bots") }} ( request_available DESC );

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- GALLERIES --------------------------------------------------------
CREATE SEQUENCE gal_categories_order_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE IF NOT EXISTS {{ t("gal_categories") }} (
  gallery_id serial PRIMARY KEY,
  name text NOT NULL,
  icon text NOT NULL,
  order_id integer NOT NULL DEFAULT nextval('gal_categories_order_id_seq'::regclass),
  published smallint NOT NULL DEFAULT '0',
  deleted smallint NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS {{ t("gallery_publics") }} (
  public_id SERIAL PRIMARY KEY UNIQUE,
  gallery_id int,
  instagram_id bigint,
  username text  NOT NULL UNIQUE,
  profile_picture text,
  full_name text,
  followers_count int DEFAULT 0,
  parsed_all_old_media boolean DEFAULT FALSE,
  deleted boolean DEFAULT FALSE
);

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- MEDIA ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("media") }} (
  media_id SERIAL PRIMARY KEY,
  public_id int,
  instagram_media_id text NOT NULL,
  likes_count int DEFAULT 0,
  comments_count int DEFAULT 0,
  datetime int NOT NULL,
  data text,
  UNIQUE ( instagram_media_id )
);

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- PEOPLE -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("people_categories") }} (
  cat_id SERIAL PRIMARY KEY NOT NULL,
  cat_name text NOT NULL,
  cat_icon text NOT NULL
);

CREATE TABLE IF NOT EXISTS {{ t("people_subcategories") }} (
  subcat_id SERIAL PRIMARY KEY NOT NULL,
  subcat_name text NOT NULL,
  subcat_icon text NOT NULL,
  cat_id int NOT NULL,
  deleted boolean NOT NULL DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS {{ t("people_publics") }} (
  public_id SERIAL PRIMARY KEY ,
  cat_id smallint,
  subcat_id smallint DEFAULT NULL,
  instagram_id bigint,
  username text NOT NULL UNIQUE,
  profile_picture text,
  full_name text,
  followers_count int DEFAULT 0,
  parsed_all_old_media boolean DEFAULT FALSE,
  deleted boolean DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS {{ t("people_statistics") }} (
  media_id SERIAL PRIMARY KEY ,
  public_id int,
  instagram_media_id text NOT NULL UNIQUE,
  likes_count int DEFAULT 0,
  comments_count int DEFAULT 0,
  datetime int NOT NULL
);

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- BRANDS -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("brands_categories") }} (
  cat_id SERIAL PRIMARY KEY NOT NULL,
  cat_name text NOT NULL,
  cat_icon text NOT NULL
);

CREATE TABLE IF NOT EXISTS {{ t("brands_subcategories") }} (
  subcat_id SERIAL PRIMARY KEY NOT NULL,
  subcat_name text NOT NULL,
  subcat_icon text NOT NULL,
  cat_id int NOT NULL,
  deleted boolean DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS {{ t("brands_publics") }} (
  public_id SERIAL PRIMARY KEY,
  cat_id smallint,
  subcat_id smallint DEFAULT NULL,
  instagram_id bigint,
  username text NOT NULL UNIQUE,
  profile_picture text,
  full_name text,
  deleted boolean NOT NULL DEFAULT FALSE
);

------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- ICONS_ORDER -------------------------------------------------------
CREATE SEQUENCE icons_sequence
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- SNAPSHOTS ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS {{ t("snapshots") }} (
  snapshot_id SERIAL PRIMARY KEY,
  snapshot_data text,
  published smallint NOT NULL DEFAULT '0',
  created integer NOT NULL DEFAULT '0'
);

------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- IMAGES_ORDER ------------------------------------------------------
CREATE SEQUENCE images_sequence
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

-----------------------------------------------------------------------------------------------------------------------
---------------------------------------------------- CATEGORIES_CONTENT -----------------------------------------------
INSERT INTO {{ t("people_categories") }}
  ( cat_name, cat_icon ) VALUES
  ( 'fashion', '' ),
  ( 'music', '' ),
  ( 'sports', '' ),
  ( 'others', '' ),
  ( 'tv', '' ),
  ( 'movies', '' );

INSERT INTO {{ t("brands_categories") }}
  ( cat_name, cat_icon ) VALUES
  ( 'food', '' ),
  ( 'fashion', '' ),
  ( 'cars', '' ),
  ( 'others', '' ),
  ( 'sports', '' ),
  ( 'media', '' );
