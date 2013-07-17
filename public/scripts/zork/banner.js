/**
 * User interface functionalities
 * @package zork
 * @subpackage banner
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
( function ( global, $, js )
{
    "use strict";

    if ( typeof js.banner !== "undefined" )
    {
        return;
    }

    /**
     * @class Banner module
     * @constructor
     * @memberOf Zork
     */
    global.Zork.Banner = function ()
    {
        this.version = "1.0";
        this.modulePrefix = [ "zork", "banner" ];
    };

    global.Zork.prototype.banner = new global.Zork.Banner();

    var accordionParams = {
            "active": false,
            "collapsible": true,
            "heightStyle": "content",
            "header": "> .banner-group > .banner-group-header"
        },
        sortableParams = {
            "axis": "y",
            "handle": ".banner-group-header",
            "stop": function( event, ui ) {
                ui.item.children( "legend" ).triggerHandler( "focusout" );
            }
        };

    /**
     * Global banners
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.globals = function ( element )
    {
        element = $( element );
        element.accordion( accordionParams );
    };

    global.Zork.Banner.prototype.globals.isElementConstructor = true;

} ( window, jQuery, zork ) );
