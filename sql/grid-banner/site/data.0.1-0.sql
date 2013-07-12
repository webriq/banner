-- remove data

DELETE FROM "module"
      WHERE "module" = 'Grid\Banner';

DELETE FROM "user_right"
      WHERE "group"     = 'banner'
        AND "resource"  = 'banner'
        AND "privilege" IN ( 'view', 'edit', 'create', 'delete' );
