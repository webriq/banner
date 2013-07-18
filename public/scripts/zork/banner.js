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

    var nextNewId = 0,
        css = '/styles/modules/Banner/admin.css',
        newId = function () {
            return "n" + ( nextNewId++ );
        },
        accordionParams = {
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
        },
        addButtons = function ( element, isGlobal ) {
            var templates = element.find( "> .type-templates" ),
                addGroup  = function () {
                    var group    = $( this ),
                        header   = group.find( "> .banner-group-header:first" ),
                        banners  = group.find( "> .banner-group-banners:first" ),
                        addType  = $( "<select>" );

                    if ( true || ! isGlobal ) { /// TODO: remove true
                        header.prepend(
                            $( "<button>" )
                                .css( "float", "right" )
                                .button( {
                                    "text": false,
                                    "icons": {
                                        "primary": "ui-icon-trash"
                                    }
                                } )
                                .click( function () {
                                    group.remove();
                                } )
                        );
                    }

                    templates.each( function () {
                        var template = $( this );

                        addType.append(
                            $( "<option>", {
                                "value": template.data( "type" ),
                                "text": js.core.translate( "banner.type." + template.data( "type" ) )
                            } )
                        );
                    } );

                    banners.prepend(
                        $( "<div>" )
                            .addClass( "banner-add" )
                            .append( addType )
                            .append(
                                $( "<button>" )
                                    .button( {
                                        "text": false,
                                        "icons": {
                                            "primary": "ui-icon-plus"
                                        }
                                    } )
                                    .click( function () {
                                        var type     = addType.val(),
                                            template = templates.filter( "[data-type='" + type + "']:first" );

                                        banners.append(
                                            $( template.data( "template" ) )
                                                .each( addBanner )
                                        );
                                    } )
                            )
                    );
                },
                addBanner = function () {
                    var banner   = $( this ),
                        title    = banner.find( "> .banner-title:first" );

                    title.prepend(
                        $( "<button>" )
                            .css( "float", "right" )
                            .button( {
                                "text": false,
                                "icons": {
                                    "primary": "ui-icon-trash"
                                }
                            } )
                            .click( function () {
                                banner.remove();
                            } )
                    );
                };

            element.find( "> .banner-group" )
                   .each( addGroup )
                   .find( "> .banner" )
                   .each( addBanner );

            return addGroup;
        };

    /**
     * Global banners
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.globals = function ( element )
    {
        js.style( css );
        element = $( element );
        element.accordion( accordionParams );
        addButtons( element, true );
    };

    global.Zork.Banner.prototype.globals.isElementConstructor = true;

} ( window, jQuery, zork ) );
