-- insert default values for table: module

INSERT INTO "module" ( "module", "enabled" )
     VALUES ( 'Grid\Banner', FALSE );

-- insert default values for table: user_right

INSERT INTO "user_right" ( "label", "group", "resource", "privilege", "optional", "module" )
     VALUES ( NULL, 'banner', 'banner', 'view', TRUE, 'Grid\Banner' ),
            ( NULL, 'banner', 'banner', 'edit', TRUE, 'Grid\Banner' ),
            ( NULL, 'banner', 'banner', 'create', TRUE, 'Grid\Banner' ),
            ( NULL, 'banner', 'banner', 'delete', TRUE, 'Grid\Banner' );
