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

CREATE TABLE "banner_params"
(
    "bannerId"  INTEGER             NOT NULL,
    "name"      CHARACTER VARYING   NOT NULL,
    "value"     CHARACTER VARYING   NOT NULL,

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

CREATE OR REPLACE FUNCTION "banner_random"( "set"       INTEGER,
                                            "language"  CHARACTER VARYING,
                                            "locale"    CHARACTER VARYING,
                                            "tags"      INTEGER ARRAY,
                                            "blocked"   INTEGER ARRAY DEFAULT ARRAY[]::INTEGER[] )
                   RETURNS INTEGER
                       SET search_path FROM CURRENT
                           STABLE
                  LANGUAGE plpgsql
                        AS $$
DECLARE
    "result"    INTEGER;
BEGIN

        SELECT "banner"."id"
          INTO "result"
          FROM "banner"
     LEFT JOIN "banner_x_set_by_global"
            ON "banner_x_set_by_global"."bannerId"  = "banner"."id"
           AND "banner_x_set_by_global"."setId"     = "set"
     LEFT JOIN "banner_x_set_by_locale"
            ON "banner_x_set_by_locale"."bannerId"  = "banner"."id"
           AND "banner_x_set_by_locale"."setId"     = "set"
           AND "banner_x_set_by_locale"."locale"    IN ( "language", "locale" )
     LEFT JOIN "banner_x_set_by_tag"
            ON "banner_x_set_by_tag"."bannerId"     = "banner"."id"
     LEFT JOIN "banner_set_x_tag"
            ON "banner_x_set_by_tag"."setXTagId"    = "banner_set_x_tag"."id"
           AND "banner_set_x_tag"."setId"           = "set"
           AND "banner_set_x_tag"."tagId"           = ANY ( "tags" )
    INNER JOIN "banner_set"
            ON "banner_set"."id" IN (
                   "banner_x_set_by_global"."setId",
                   "banner_x_set_by_locale"."setId",
                   "banner_set_x_tag"."setId"
               )
      ORDER BY CASE "banner"."id"
                   WHEN ANY ( "blocked" ) THEN 1
                   ELSE 0
               END ASC,
               CASE
                   WHEN "banner_set_x_tag"."priority" IS NOT NULL
                        THEN 3 + "banner_set_x_tag"."priority"
                   WHEN "banner_x_set_by_locale"."setId" IS NOT NULL
                        THEN CASE "banner_x_set_by_locale"."locale"
                                 WHEN "locale"   THEN 2
                                 WHEN "language" THEN 1
                                 ELSE 0
                             END
                   WHEN "banner_x_set_by_global"."setId" IS NOT NULL
                        THEN 0
                   ELSE -1
               END DESC,
               RANDOM() ASC
         LIMIT 1;

    RETURN "result";

END $$;
