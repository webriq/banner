--------------------------------------------------------------------------------
-- table: banner                                                              --
--------------------------------------------------------------------------------

CREATE TABLE "banner"
(
    "id"        SERIAL              NOT NULL,
    "type"      CHARACTER VARYING   NOT NULL,

    PRIMARY KEY ( "id" )
);

--------------------------------------------------------------------------------
-- table: banner_params                                                       --
--------------------------------------------------------------------------------

CREATE TABLE "banner_property"
(
    "bannerId"  INTEGER             NOT NULL,
    "name"      CHARACTER VARYING   NOT NULL,
    "value"     CHARACTER VARYING   NULL,

    PRIMARY KEY ( "bannerId", "name" ),
    FOREIGN KEY ( "bannerId" )
     REFERENCES "banner" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

--------------------------------------------------------------------------------
-- table: banner_set                                                          --
--------------------------------------------------------------------------------

CREATE TABLE "banner_set"
(
    "id"        SERIAL              NOT NULL,
    "name"      CHARACTER VARYING   NOT NULL,

    PRIMARY KEY ( "id" )
);

--------------------------------------------------------------------------------
-- table: banner_x_set_by_global                                              --
--------------------------------------------------------------------------------

CREATE TABLE "banner_x_set_by_global"
(
    "bannerId"  INTEGER     NOT NULL,
    "setId"     INTEGER     NOT NULL,

    PRIMARY KEY ( "bannerId", "setId" ),
    FOREIGN KEY ( "bannerId" )
     REFERENCES "banner" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE,
    FOREIGN KEY ( "setId" )
     REFERENCES "banner_set" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

--------------------------------------------------------------------------------
-- table: banner_x_set_by_locale                                              --
--------------------------------------------------------------------------------

CREATE TABLE "banner_x_set_by_locale"
(
    "bannerId"  INTEGER             NOT NULL,
    "setId"     INTEGER             NOT NULL,
    "locale"    CHARACTER VARYING   NOT NULL,

    PRIMARY KEY ( "bannerId", "setId", "locale" ),
    FOREIGN KEY ( "bannerId" )
     REFERENCES "banner" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE,
    FOREIGN KEY ( "setId" )
     REFERENCES "banner_set" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

--------------------------------------------------------------------------------
-- table: banner_set_x_tag                                                    --
--------------------------------------------------------------------------------

CREATE TABLE "banner_set_x_tag"
(
    "id"        SERIAL      NOT NULL,
    "setId"     INTEGER     NOT NULL,
    "tagId"     INTEGER     NOT NULL,
    "priority"  INTEGER     NOT NULL,

    PRIMARY KEY ( "id" ),
    FOREIGN KEY ( "setId" )
     REFERENCES "banner_set" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE,
    FOREIGN KEY ( "tagId" )
     REFERENCES "tag" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

CREATE INDEX ON "banner_set_x_tag" ( "setId"    ASC  );
CREATE INDEX ON "banner_set_x_tag" ( "tagId"    ASC  );
CREATE INDEX ON "banner_set_x_tag" ( "priority" DESC );

--------------------------------------------------------------------------------
-- table: banner_x_set_by_tag                                                 --
--------------------------------------------------------------------------------

CREATE TABLE "banner_x_set_by_tag"
(
    "bannerId"  INTEGER     NOT NULL,
    "setXTagId" INTEGER     NOT NULL,

    PRIMARY KEY ( "bannerId", "setXTagId" ),
    FOREIGN KEY ( "bannerId" )
     REFERENCES "banner" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE,
    FOREIGN KEY ( "setXTagId" )
     REFERENCES "banner_set_x_tag" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);

--------------------------------------------------------------------------------
-- function: banner_random(int, varchar, varchar, int[], int[])               --
--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION "banner_random"( "p_set"         INTEGER,
                                            "p_language"    CHARACTER VARYING,
                                            "p_locale"      CHARACTER VARYING,
                                            "p_tags"        INTEGER[],
                                            "p_blocked"     INTEGER[]           DEFAULT ARRAY[]::INTEGER[],
                                            "p_mul"         DOUBLE PRECISION    DEFAULT 1 )
                   RETURNS INTEGER
                       SET search_path FROM CURRENT
                           STABLE
                  LANGUAGE plpgsql
                        AS $$
DECLARE
    "v_result"    INTEGER;
BEGIN

        SELECT "banner"."id"
          INTO "v_result"
          FROM "banner"
     LEFT JOIN "banner_x_set_by_global"
            ON "banner_x_set_by_global"."bannerId"  = "banner"."id"
           AND "banner_x_set_by_global"."setId"     = "p_set"
     LEFT JOIN "banner_x_set_by_locale"
            ON "banner_x_set_by_locale"."bannerId"  = "banner"."id"
           AND "banner_x_set_by_locale"."setId"     = "p_set"
           AND "banner_x_set_by_locale"."locale"    IN ( "p_language", "p_locale" )
     LEFT JOIN "banner_x_set_by_tag"
            ON "banner_x_set_by_tag"."bannerId"     = "banner"."id"
     LEFT JOIN "banner_set_x_tag"
            ON "banner_x_set_by_tag"."setXTagId"    = "banner_set_x_tag"."id"
           AND "banner_set_x_tag"."setId"           = "p_set"
           AND "banner_set_x_tag"."tagId"           = ANY ( "p_tags" )
     LEFT JOIN "tag"
            ON "banner_set_x_tag"."tagId"           = "tag"."id"
    INNER JOIN "banner_set"
            ON "banner_set"."id" IN (
                   "banner_x_set_by_global"."setId",
                   "banner_x_set_by_locale"."setId",
                   "banner_set_x_tag"."setId"
               )
         WHERE "tag"."locale" IS NULL
            OR "tag"."locale" IN ( "p_language", "p_locale" )
      ORDER BY "p_mul" * CASE
                   WHEN "banner_set_x_tag"."priority" IS NOT NULL
                        THEN "p_mul" * ( 3 + "banner_set_x_tag"."priority" )
                   WHEN "banner_x_set_by_locale"."setId" IS NOT NULL
                        THEN CASE "banner_x_set_by_locale"."locale"
                                 WHEN "p_locale"   THEN 2
                                 WHEN "p_language" THEN 1
                                 ELSE 0
                             END
                   WHEN "banner_x_set_by_global"."setId" IS NOT NULL
                        THEN 0
                   ELSE 0
               END - CASE
                   WHEN "banner"."id" = ANY ( "p_blocked" ) THEN 1
                   ELSE 0
               END + RANDOM() DESC
         LIMIT 1;

    RETURN "v_result";

END $$;

--------------------------------------------------------------------------------
-- function: banner_x_set_delete_trigger()                                    --
--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION "banner_x_set_delete_trigger"()
                   RETURNS TRIGGER
                       SET search_path FROM CURRENT
                  LANGUAGE plpgsql
                        AS $$
BEGIN

    DELETE FROM "banner"
          WHERE "id" = OLD."bannerId";

    RETURN NULL;

END $$;

--------------------------------------------------------------------------------
-- trigger: banner_x_set_by_global.1000__banner_x_set_by_global_delete        --
--------------------------------------------------------------------------------

CREATE TRIGGER "1000__banner_x_set_by_global_delete"
         AFTER DELETE
            ON "banner_x_set_by_global"
           FOR EACH ROW
       EXECUTE PROCEDURE "banner_x_set_delete_trigger"();

--------------------------------------------------------------------------------
-- trigger: banner_x_set_by_locale.1000__banner_x_set_by_locale_delete        --
--------------------------------------------------------------------------------

CREATE TRIGGER "1000__banner_x_set_by_locale_delete"
         AFTER DELETE
            ON "banner_x_set_by_locale"
           FOR EACH ROW
       EXECUTE PROCEDURE "banner_x_set_delete_trigger"();

--------------------------------------------------------------------------------
-- trigger: banner_x_set_by_tag.1000__banner_x_set_by_tag_delete              --
--------------------------------------------------------------------------------

CREATE TRIGGER "1000__banner_x_set_by_tag_delete"
         AFTER DELETE
            ON "banner_x_set_by_tag"
           FOR EACH ROW
       EXECUTE PROCEDURE "banner_x_set_delete_trigger"();
