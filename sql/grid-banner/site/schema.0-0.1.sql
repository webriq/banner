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

    PRIMARY KEY ( "id" ),
    FOREIGN KEY ( "bannerId" )
     REFERENCES "banner" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE,
    FOREIGN KEY ( "setXTagId" )
     REFERENCES "banner_set_x_tag" ( "id" )
      ON UPDATE CASCADE
      ON DELETE CASCADE
);
