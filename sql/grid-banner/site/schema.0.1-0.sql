-- drop triggers

DROP TRIGGER "1000__banner_x_set_by_tag_delete" ON "banner_x_set_by_tag" CASCADE;
DROP TRIGGER "1000__banner_x_set_by_locale_delete" ON "banner_x_set_by_locale" CASCADE;
DROP TRIGGER "1000__banner_x_set_by_global_delete" ON "banner_x_set_by_global" CASCADE;
DROP FUNCTION "banner_x_set_delete_trigger"() CASCADE;

-- drop functions

DROP FUNCTION "banner_random"( INTEGER,
                               CHARACTER VARYING,
                               CHARACTER VARYING,
                               INTEGER[],
                               INTEGER[] ) CASCADE;

-- drop tables

DROP TABLE "banner_x_set_by_tag" CASCADE;
DROP TABLE "banner_set_x_tag" CASCADE;
DROP TABLE "banner_x_set_by_locale" CASCADE;
DROP TABLE "banner_x_set_by_global" CASCADE;
DROP TABLE "banner_set" CASCADE;
DROP TABLE "banner_property" CASCADE;
DROP TABLE "banner" CASCADE;
