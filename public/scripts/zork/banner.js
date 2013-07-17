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

    /**
     * Global banners
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.globals = function ( element )
    {
        element = $( element );

        element.accordion( {
            "collapsible": true,
            "heightStyle": "content",
            "header": "> fieldset > legend"
        } );
    };

    global.Zork.Banner.prototype.globals.isElementConstructor = true;

} ( window, jQuery, zork ) );
