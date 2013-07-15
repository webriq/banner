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
DROP TABLE "banner_params" CASCADE;
DROP TABLE "banner" CASCADE;
