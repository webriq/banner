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
        css = "/styles/modules/Banner/admin.css",
        newId = function () {
            return "n" + ( nextNewId++ );
        },
        accordionParams = {
            "active": false,
            "collapsible": true,
            "heightStyle": "content",
            "header": "> .banner-group > .banner-group-header",
            "activate": function () {
                if ( false !== $( this ).accordion( "option", "active" ) )
                {
                    $( this ).closest( "form" )
                             .find( ":ui-accordion" )
                             .not( this )
                             .accordion( "option", "active", false );
                }
            }
        },
        sortableParams = {
            "axis": "y",
            "items": "> .banner-group",
            "handle": ".banner-group-header",
            "stop": function( event, ui ) {
                ui.item.children( ".banner-group-header" ).triggerHandler( "focusout" );
            }
        },
        scrollTo = function ( element ) {
            element = $( element );

            if ( typeof element[0].scrollIntoView === "function" ) {
                element[0].scrollIntoView();
            } else {
                var o = element.offset();
                $( "html, body" ).animate( {
                    "scrollTop": o.top - 20,
                    "scrollLeft": o.left - 20
                } );
            }
        },
        addButtons = function ( element, templateTranslations ) {
            element.addClass( "banner-group-list" );

            var templates = element.find( "> .type-template" ),
                addGroup  = function () {
                    var group    = $( this ),
                        header   = group.find( "> .banner-group-header:first" ),
                        banners  = group.find( "> .banner-group-banners:first" ),
                        addType  = $( "<select>" );

                    if ( templateTranslations ) {
                        header.prepend(
                            $( "<button type='button'>" )
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
                                $( "<button type='button'>" )
                                    .button( {
                                        "text": false,
                                        "icons": {
                                            "primary": "ui-icon-plus"
                                        }
                                    } )
                                    .click( function () {
                                        var i, type  = addType.val(),
                                            template = templates.filter( "[data-type='" + type + "']:first" ),
                                            banner   = String( template.data( "template" ) )
                                                           .replace( /__index__/g, newId() );

                                        for ( i in templateTranslations )
                                        {
                                            banner = banner.replace(
                                                new RegExp( i, "g" ),
                                                templateTranslations[i]( group )
                                            );
                                        }

                                        banner = $( banner );
                                        banners.append( banner );
                                        banner.each( addBanner );
                                        scrollTo( banner );
                                    } )
                            )
                            .inputset()
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
                   .find( "> .banner-group-banners > .banner" )
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
        addButtons( element, false );
    };

    global.Zork.Banner.prototype.globals.isElementConstructor = true;

    /**
     * Locale banners
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.locales = function ( element )
    {
        js.style( css );
        element = $( element );
        element.accordion( accordionParams );

        var lang,
            langgrp,
            locales     = element.data( "locales" ),
            localegrp   = {},
            addLocale   = $( "<select>" ),
            addDiv      = $( "<div>" ).addClass( "banner-group-add" ),
            addGroup    = addButtons( element, {
                "__locale__": function ( group ) {
                    return group.data( "locale" );
                }
            } );

        $.each( locales, function ( _, locale ) {
            var lang = locale.substr( 0, 2 );

            if ( ! ( lang in localegrp ) )
            {
                localegrp[lang] = [];
            }

            localegrp[lang].push( locale );
        } );

        for ( lang in localegrp )
        {
            switch ( localegrp[lang].length )
            {
                case 0:
                    break;

                case 1:
                    addLocale.append(
                        $( "<option>", {
                            "value": localegrp[lang][0],
                            "text": js.core.translate( "locale.sub." + localegrp[lang][0] )
                        } )
                    );
                    break;

                default:
                    addLocale.append(
                        langgrp = $( "<optgroup>", {
                            "label": js.core.translate( "locale.main." + lang )
                        } )
                    );

                    $.each( localegrp[lang], function ( _, locale ) {
                        langgrp.append(
                            $( "<option>", {
                                "value": locale,
                                "text": js.core.translate( "locale.sub." + locale )
                            } )
                        );
                    } );
                    break;
            }
        }

        element.prepend( addDiv );
        addDiv.append( addLocale )
              .append(
                  $( "<button type='button'>" )
                      .button( {
                          "text": false,
                          "icons": {
                              "primary": "ui-icon-plus"
                          }
                      } )
                      .click( function () {
                          var locale = addLocale.val(),
                              found  = element.find( "> .banner-group[data-locale='" + locale + "']" ),
                              group;

                          if ( found.length )
                          {
                              scrollTo( found );
                              return;
                          }

                          addDiv.after( group = $( "<div>" ) );

                          group.addClass( "banner-group" )
                               .attr( "data-locale", locale )
                               .data( "locale", locale )
                               .append(
                                   $( "<div>" )
                                       .addClass( "banner-group-header" )
                                       .text( addLocale.find( ":selected" ).text() )
                               )
                               .append(
                                   $( "<div>" )
                                       .addClass( "banner-group-banners" )
                               );

                          element.accordion( "refresh" );
                          group.each( addGroup );
                          scrollTo( group );
                      } )
              )
              .inputset();
    };

    global.Zork.Banner.prototype.locales.isElementConstructor = true;

    /**
     * Tag banners
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.tags = function ( element )
    {
        js.style( css );
        element = $( element );
        element.accordion( accordionParams )
               .sortable( sortableParams );

        var addItem,
            addTag      = $( '<input type="search">' ),
            addDiv      = $( "<div>" ).addClass( "banner-group-add" ),
            addGroup    = addButtons( element, {
                "__tagid__": function ( group ) {
                    return group.data( "tagid" );
                }
            } ),
            add = function ( tag ) {
                var found  = element.find( "> .banner-group[data-tagid='" + tag.id + "']" ),
                    group;

                if ( found.length )
                {
                    scrollTo( found );
                    return;
                }

                addDiv.after( group = $( "<div>" ) );

                group.addClass( "banner-group" )
                     .attr( "data-tagid", tag.id )
                     .data( "tagid", tag.id )
                     .append(
                         $( "<div>" )
                             .addClass( "banner-group-header" )
                             .text( tag.value + (
                                 tag.locale
                                     ? " (" + js.core.translate( "locale.sub." + tag.locale ) + ")"
                                     : ""
                             ) )
                     )
                     .append(
                         $( "<div>" )
                             .addClass( "banner-group-banners" )
                     );

                element.accordion( "refresh" )
                       .sortable( "refresh" );
                group.each( addGroup );
                scrollTo( group );

                addItem = null;
                setTimeout( function () {
                    addTag.val( "" );
                }, 1 );
            };

        element.prepend( addDiv );
        addDiv.append(
                  addTag.autocomplete( {
                      "source": "/app/" + js.core.defaultLocale + "/tag/search",
                      "minLength": 2,
                      "select": function ( event, ui ) {
                          if ( ! ui || ! ui.item || ! ui.item.id )
                          {
                              return;
                          }

                          add( ui.item );

                          setTimeout( function () {
                              addTag.val( "" );
                          }, 1 );
                      },
                      "response": function ( event, ui ) {
                          addItem = null;

                          if ( ! ui || ! ui.content || ! ui.content.length )
                          {
                              return;
                          }

                          var val = addTag.val();

                          $.each( ui.content, function ( _, item ) {
                              if ( item.value === val )
                              {
                                  addItem = item;
                                  return false;
                              }
                          } );
                      }
                  } )
              )
              .append(
                  $( "<button type='button'>" )
                      .button( {
                          "text": false,
                          "icons": {
                              "primary": "ui-icon-plus"
                          }
                      } )
                      .click( function () {
                          if ( ! addItem )
                          {
                              return;
                          }

                          add( addItem );
                      } )
              )
              .inputset();
    };

    global.Zork.Banner.prototype.tags.isElementConstructor = true;

    /**
     * Priority multiplier
     *
     * @memberOf Zork.Banner
     */
    global.Zork.Banner.prototype.priorityMul = function ( element )
    {
        element = $( element );
    };

    global.Zork.Banner.prototype.priorityMul.isElementConstructor = true;

} ( window, jQuery, zork ) );
