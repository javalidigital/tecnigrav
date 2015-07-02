/* library(common functions) for header */
(function($, H) {
    'use strict';

    var ATTR_MENU_SLIDEDOWN_DURATION = 'data-menu-slidedown-duration',
        ATTR_MENU_SLIDEUP_DURATION = 'data-menu-slideup-duration',
        ATTR_MENU_FADEIN_DURATION = 'data-menu-fadein-duration',
        ATTR_MENU_FADEOUT_DURATION = 'data-menu-fadeout-duration';

    H.MENU_SLIDEDOWN_DURATION = 150;
    H.MENU_SLIDEUP_DURATION = 150;
    H.MENU_FADEIN_DURATION = 150;
    H.MENU_FADEOUT_DURATION = 150;

    // hover internal and leave timeout must be same, otherwise the activation of items in .mobile-nav will be disordered
    H.HOVER_INTERVAL = H.HOVER_LEAVE_TIMEOUT = 200;

    H.IS_MENU_ITEM_WITH_WIDGET_VISIBLE = $('header').hasClass('lr-mi-with-widget-visible');

    // module design pattern
    // menu states and operations
    H.menuState = (function() {
        var state = {};

        state.lowResolution = {};
        state.lowResolution.expandedMenuTriggers = [];
        state.highResolution = {};
        // indicate if mouse is hovering on areas that menu box should not hide
        // used for .h1 main-heder, .h2 .mainmenu, .search-box, .shopping-bag
        // do not used for first level menu items
        state.isHoverOnMenuBoxInsensitiveArea = false;

        return {
            storeExpandedMenuTrigger: function($menuTrigger) {
                if ($menuTrigger) {
                    if (!$menuTrigger instanceof jQuery) {
                        throw new Error('Invalid parameter. It should be jQuery object.');
                    }

                    for (var i = 0; i < state.lowResolution.expandedMenuTriggers.length; i++) {
                        if (state.lowResolution.expandedMenuTriggers[i].is($menuTrigger)) {
                            return;
                        }
                    }

                    state.lowResolution.expandedMenuTriggers.push($menuTrigger);
                }
            },
            removeExpandedMenuTrigger: function($menuTrigger) {
                if ($menuTrigger) {
                    if (!$menuTrigger instanceof jQuery) {
                        throw new Error('Invalid parameter. It should be jQuery object.');
                    }

                    for (var i = state.lowResolution.expandedMenuTriggers.length - 1; i >= 0; i--) {
                        if (state.lowResolution.expandedMenuTriggers[i].is($menuTrigger)) {
                            state.lowResolution.expandedMenuTriggers.splice(i, 1);
                        }
                    }
                }
            },
            getExpandedMenuTriggers: function() {
                return state.lowResolution.expandedMenuTriggers;
            },
            setHoverOnMenuBoxInsensitiveArea: function(isHoverOn) {
                state.isHoverOnMenuBoxInsensitiveArea = isHoverOn
                ;
            },
            getHoverOnMenuBoxInsensitiveArea: function() {
                return state.isHoverOnMenuBoxInsensitiveArea;
            }
        };
    }());

    // module design pattern
    // execute the window resize event handler by their priority
    H.resizeQueue = (function() {
        var queue = [];

        return {
            push: function(resizeHandler, priority) {
                var i, j, item;

                if(!resizeHandler instanceof Function) {
                    throw new Error('Invalid parameter. It should be function.');
                }

                priority = priority? 0 : priority;

                queue.push({
                    handler: resizeHandler,
                    priority: priority
                });

                // move the new handler to the right position
                // keep the queue sorted by priority
                for(i = queue.length-1, j = i-1; i > 0; i--, j--) {
                    if(queue[i] < queue[j]) {
                        item = queue[j];
                        queue[j] = queue[i];
                        queue[i] = item;
                    } else {
                        break;
                    }
                }
            },
            iterator: function() {  // iterator pattern
                var current = queue.length;

                return {
                    next: function() {
                        current -= 1;
                        return queue[current];
                    },
                    hasNext: function() {
                        return current > 0;
                    }
                };
            }
        };

    }());

    H.isTouchDevice = function() {
        return $('html').hasClass('touch');
    };

    // do not use window.mediaQuery, because if we use polyfill for old browser, the polyfill doesn't work well with $(window).resize()
    H.isScreenWiderThan = function(width) {
        var minWidth = width + 1;
        return $('.s-' + minWidth).is(':visible');
    };

    H.isMenuAnimationEnabled = function() {
        return $('header').hasClass('menu-animation-enabled');
    };

    H.isHoverDelayEnabled = function() {
        return $('header').hasClass('hover-delay-enabled');
    };

    // Note: these two function are not only used for drop down menu, but also used for search box and shopping bag
    H.displayMenu = function($menu) {
        // make sure slideToggle() behave like slideDown() at the first time
        // (sometimes if the page haven't finish the render, slideToggle() will behave like slideUp())
        if($menu.is(':visible') && !$menu.hasClass('display-ready')) {
            return;
        } else if(!$menu.is(':visible') && !$menu.hasClass('display-ready')) {
            $menu.addClass('display-ready');
        } else {

        }
        
        if (H.isMenuAnimationEnabled()) {
            if (H.MENU_SLIDEDOWN_DURATION !== 0 && H.MENU_FADEIN_DURATION !== 0) {
                // if there is no opacity set for this menu, set it to 0 before the animation begin
                if ($menu.css('opacity') === '1') {
                    //$menu.css('opacity', '0');
                }

                $menu.stop(true).slideToggle({
                    duration: H.MENU_SLIDEDOWN_DURATION
                }).queue("fd", function(next) {
                    $(this).animate({
                        opacity: 1
                    }, {
                        duration: H.MENU_FADEIN_DURATION,
                        queue: false
                    });
                    next();
                }).dequeue("fd");
            } else if (H.MENU_SLIDEDOWN_DURATION !== 0 && H.MENU_FADEIN_DURATION === 0) {
                $menu.stop(true).slideToggle(H.MENU_SLIDEDOWN_DURATION);
            } else if (H.MENU_SLIDEDOWN_DURATION === 0 && H.MENU_FADEIN_DURATION !== 0) {
                $menu.stop(true).fadeToggle(H.MENU_FADEIN_DURATION);
            } else {
                $menu.stop(true).show();
            }
        } else {
            $menu.show();
        }
    };

    H.hideMenu = function($menu) {
        if (H.isMenuAnimationEnabled()) {
            if (H.MENU_SLIDEUP_DURATION !== 0 && H.MENU_FADEOUT_DURATION !== 0) {
                $menu.stop(true).slideToggle(
                    H.MENU_SLIDEUP_DURATION
                ).queue("fd", function(next) {
                    $(this).animate({
                        opacity: 0
                    }, {
                        duration: H.MENU_FADEOUT_DURATION,
                        queue: false
                    });
                    next();
                }).dequeue("fd");
            } else if (H.MENU_SLIDEUP_DURATION !== 0 && H.MENU_FADEOUT_DURATION === 0) {
                $menu.stop(true).slideToggle(H.MENU_SLIDEUP_DURATION);
            } else if (H.MENU_SLIDEUP_DURATION === 0 && H.MENU_FADEOUT_DURATION !== 0) {
                $menu.stop(true).fadeToggle(H.MENU_FADEOUT_DURATION);
            } else {
                $menu.stop(true).hide();
            }
        } else {
            $menu.hide();
        }
    };

    // Note: these two function are not only used on first-level menu, but also used on deeper level menu
    H.displayDropdownMenu = function($menuTrigger, callback) {
        if(!$menuTrigger.hasClass('submenu-expanded')) {
            $menuTrigger.addClass('submenu-expanded');
            H.displayMenu($menuTrigger.children('ul'));

            if(callback && callback instanceof Function) {
                callback($menuTrigger);
            }
        }
    };

    H.hideDropdownMenu = function($menuTrigger, callback) {
        if($menuTrigger.hasClass('submenu-expanded')) {
            $menuTrigger.removeClass('submenu-expanded');
            H.hideMenu($menuTrigger.children('ul'));

            if(callback && callback instanceof Function) {
                callback.apply($menuTrigger);
            }
        }
    };

    H.resizeGoogleMap = function($menuItem) {
        if($menuItem.find('.gmap').data('mapObject') && window.google.maps) {
            var map = $menuItem.find('.gmap').data('mapObject'),
                marker = $menuItem.find('.gmap').data('mapMarker');

            window.google.maps.event.trigger(map, 'resize');
            map.setCenter(marker.position);
        }
    }

    H.collapseSearchShoppingMenuAnyway = function() {
        // collpase the expanded search box or shopping bag
        if (!$('.mobile-nav').hasClass('dropdown-on-hover-enabled')) {
            $(".mobile-nav li a.active").triggerHandler('click');
        } else {
            // do not test isHoverOnMenuBoxInsensitiveArea at here, close menu box anyway
            H.expandedMenuBoxHide();
        }
    }

    H.displayTopLevelDropdownMenu = function($menuTrigger, callback) {
        if (H.isScreenWiderThan(980)) {
            H.displayDropdownMenu($menuTrigger, function($menuTrigger) {
                H.resizeGoogleMap($menuTrigger);
                H.collapseSearchShoppingMenuAnyway();
            });

            if(callback && callback instanceof Function) {
                callback($menuTrigger);
            }
        }
    };

    H.hideTopLevelDropdownMenu = function($menuTrigger) {
        if (H.isScreenWiderThan(980)) {
            H.hideDropdownMenu($menuTrigger);
        }
    };

    H.getHeader = function($element) {
        if (!$element instanceof jQuery) {
            throw new Error('Invalid parameter. It should be jQuery object.');
        }
        return $element.closest('header');
    };

    // template function for expand/collapse menu with .collapsed
    H.collapsedMenuToggle = function($menuTrigger, menuToggle, otherExpandedMenuToggle) {
        if (!$menuTrigger instanceof jQuery) {
            throw new Error('Invalid parameter. It should be jQuery object.');
        }

        // collapse other active menus
        $(".collapsed.active").not($menuTrigger).each(function() {
            if (otherExpandedMenuToggle && otherExpandedMenuToggle instanceof Function) {
                otherExpandedMenuToggle($(this));
            } else {
                $(this).removeClass("active").parents("li").removeClass("active");
                $($(this).data("target"), H.getHeader($(this))).hide();
            }

            H.menuState.removeExpandedMenuTrigger($(this));
        });

        var $target = $($menuTrigger.data("target"), H.getHeader($menuTrigger));
        $target.data('trigger', $menuTrigger);
        // use callback function for different effect
        if (menuToggle && menuToggle instanceof Function) {
            menuToggle($target);
        } else {
            $target.show();
        }

        if (!$menuTrigger.hasClass("active")) {
            $menuTrigger.addClass("active").parents("li").addClass("active");
            // no need to record the button in .mobile-nav
            if ($menuTrigger.closest('.mobile-nav').length === 0) {
                H.menuState.storeExpandedMenuTrigger($menuTrigger);
            }
        } else {
            $menuTrigger.removeClass("active").parents("li").removeClass("active");
            H.menuState.removeExpandedMenuTrigger($menuTrigger);
        }
    };

    // menu box display and hide, for example, search box and cart box
    // only be used when > 980px, for < 980px, there are other logics.
    H.menuBoxDisplay = function($menuBoxTrigger) {
        if (H.isScreenWiderThan(980) && !$menuBoxTrigger.hasClass('active')) {
            H.collapsedMenuToggle($menuBoxTrigger, function($menu) {
                H.displayMenu($menu);
            }, function($otherExpandedMenuTrigger) {
                // only hide menu box in .mobile-nav
                if($otherExpandedMenuTrigger.closest('.mobile-nav').length > 0) {
                    H.menuBoxHide($otherExpandedMenuTrigger);
                }
            });
        }
    };

    H.menuBoxHide = function($menuBoxTrigger) {
        if (H.isScreenWiderThan(980) && $menuBoxTrigger.hasClass('active')) {
            H.collapsedMenuToggle($menuBoxTrigger, function($menu) {
                H.hideMenu($menu);
            });
        }
    };

    H.expandedMenuBoxHide = function() {
        $('.mobile-nav li a.active').each(function() {
            H.menuBoxHide($(this));
        });
    };

    H.setMegamenuTopPosition = function(megamenuItem) {
        // calculate the top position megamenu should be placed
        // megamenu always display at the bottom of its trigger element
        var $megamenuItemEle = $(megamenuItem),
            megamenuItemEleOffset = $megamenuItemEle.offset(),
            referenceEleOffset = $(megamenuItem).offsetParent().offset(),
            megamenuTop;

        if (typeof megamenuItemEleOffset !== 'undefined' && typeof referenceEleOffset !== 'undefined') {
            megamenuTop = megamenuItemEleOffset.top + $megamenuItemEle.innerHeight() - referenceEleOffset.top;

            $(megamenuItem).children('ul').css('top', megamenuTop);
        }
    };

    H.isTopMenuItemsHasDescription = function($menu) {
        var $menuItems = $menu.children('li');

        for(var i = 0; i < $menuItems.length; i++) {
            if($($menuItems[i]).hasClass('mi-with-description')) {
                return true;
            }
        }

        return false;
    }

    H.screenSwitchHandler = function(mobileScreenHandler, largeScreenHandler) {
        var previewScreen;

        if(H.isScreenWiderThan(980)) {
            previewScreen = 'large';
        } else {
            previewScreen = 'mobile';
        }

        $(window).resize(function() {
            if (H.isScreenWiderThan(980)) {
                if(previewScreen === 'mobile') {
                    previewScreen = 'large';
console.log('window resize1');
                    largeScreenHandler();
                }
            } else {
                if(previewScreen === 'large') {
                    previewScreen = 'mobile';
console.log('window resize2');
                    mobileScreenHandler();
                }
            }
        });
    }

    // detect ie and its version
    H.ie = (function() {
        var v = 3, 
        div = document.createElement('div'), 
        a = div.all || [];

        while (
            div.innerHTML = '<!--[if gt IE '+(++v)+']><br><![endif]-->', 
            a[0]
        );

        return v > 4 ? v : !v;
    }());

    $(document).ready(function() {
        /* set the menu animation duration */
        var $header = $('header');
        if ($header.attr(ATTR_MENU_SLIDEDOWN_DURATION)) {
            H.MENU_SLIDEDOWN_DURATION = parseInt($header.attr(ATTR_MENU_SLIDEDOWN_DURATION));
        }
        if ($header.attr(ATTR_MENU_SLIDEUP_DURATION)) {
            H.MENU_SLIDEUP_DURATION = parseInt($header.attr(ATTR_MENU_SLIDEUP_DURATION));
        }
        if ($header.attr(ATTR_MENU_FADEIN_DURATION)) {
            H.MENU_FADEIN_DURATION = parseInt($header.attr(ATTR_MENU_FADEIN_DURATION));
        }
        if ($header.attr(ATTR_MENU_FADEOUT_DURATION)) {
            H.MENU_FADEOUT_DURATION = parseInt($header.attr(ATTR_MENU_FADEOUT_DURATION));
        }

        $('header').append('<div class="s-801"/>').append('<div class="s-981"/>');
    });

    $(window).resize(function(event) {
        var it = H.resizeQueue.iterator();
        while(it.hasNext()) {
            it.next().handler();
        }
    });

}(jQuery, window.themeHeader = window.themeHeader || {}));

/* search, social effect */
(function($) {
    // search
    $(document).ready(function() {
        $("header .searchform input").focus(function() {
            $(this).parents(".searchform").addClass("active");
        }).blur(function() {
            $(this).parents(".searchform").removeClass("active");
        });
    });

    // social
    $(document).ready(function() {
        // tooltip
        // store the value of title to data() when document ready
        $("header .social li > a[title]").each(function() {
            $(this).data('title', $(this).attr('title')).removeAttr('title');
        }).hover(function() {
            $(this).parent().append("<span class='tooltip-title'>" + $(this).data("title") + "</span>");
            var $tooltip = $(this).next();
            var W = $(this).parent().width();
            var w = $tooltip.outerWidth();
            var c = (w - W) / 2;
            $tooltip.css({
                "left": -c
            });
        }, function() {
            $(this).next(".tooltip-title").remove();
        });
    });

}(jQuery));

/*
 * sticky headers
 */

(function($, H, window) {
    'use strict';

    // determine if the header have collpase effect

    function hasCollapseEffect() {
        return $("header.sticky-enabled").hasClass('sticky-collapse');
    }

    // determine if the header is in the "collaping" animation

    function isCollapsing() {
        return $("header.sticky").hasClass('collapsing');
    }

    function collapseStickyHeader() {
        // if the header is in the collapsing animation, ignore the other scroll event
        if (isCollapsing()) {
            return;
        }

        var $standardHeader = $("header.standard"),
            $stickyHeader = $("header.sticky"),
            stickyHeaderHeight = $stickyHeader.innerHeight();

        // if the sticky header is in the expanding animation, stop it
        if ($stickyHeader.is(':animated')) {
            $stickyHeader.stop();
        }

        // use class 'collapsing' to indicate if the sticky header is collapsing
        $stickyHeader.addClass('collapsing').animate({
            top: -stickyHeaderHeight
        }, 400, function() {
            $standardHeader.remove();
            $stickyHeader.removeClass('sticky collapsing').css('top', '').find('li.dropdownmenu-mega > ul:visible').css({
                    top: function() {
                        return H.setMegamenuTopPosition($(this).parent());
                    }
                });
        });
    }

    function clearStickyHeader() {
        var $standardHeader = $("header.standard"),
            $stickyHeader = $("header.sticky");

        if ($stickyHeader.length > 0) {
            $standardHeader.remove();
            $stickyHeader.removeClass('sticky').find('li.dropdownmenu-mega > ul:visible').css({
                    top: function() {
                        return H.setMegamenuTopPosition($(this).parent());
                    }
                });
        }
    }

    function hideStickyHeader() {
        var $standardHeader = $('header.standard'),
            $stickyHeader = $('header.sticky'),
            standardHeaderHeight = $standardHeader.innerHeight(),
            stickyHeaderHeight = $stickyHeader.innerHeight(),
            scrollTop = $(window).scrollTop(),
            stickyTriggerPosition = $standardHeader.data('stickyTriggerPosition');

        if (typeof stickyTriggerPosition === 'undefined') {
            if (!hasCollapseEffect()) {
                stickyTriggerPosition = standardHeaderHeight - stickyHeaderHeight;
            } else {
                stickyTriggerPosition = standardHeaderHeight;
            }
        }

        if (scrollTop <= stickyTriggerPosition) {
            if (!hasCollapseEffect()) {
                clearStickyHeader();
            } else {
                collapseStickyHeader();
            }
        }
    }

    function displayStickyHeader() {
        if ($('header.sticky').length > 0) {
            return;
        }

        var $header = $("header.sticky-enabled"),
            headerHeight = $header.innerHeight(),
            scrollTop = $(window).scrollTop(),
            stickyTriggerPosition = $header.data('stickyTriggerPosition'),
            $standardHeader;

        if (typeof stickyTriggerPosition === 'undefined') {
            stickyTriggerPosition = headerHeight;
        }

        if (scrollTop > stickyTriggerPosition) {
            $standardHeader = $header.clone(true).addClass('standard').insertBefore($header).find('li.dropdownmenu > ul:visible').hide().end();
            $header.css('visibility', 'hidden').addClass("sticky").css({
                    top: function() {
                        return -$(this).innerHeight();
                    }
                })
                .find('li.dropdownmenu-mega > ul:visible').css({
                    top: function() {
                        return H.setMegamenuTopPosition($(this).parent());
                    }
                }).end().css('visibility', '').animate({
                    top: 0
                }, 400, function() {
                    if (!hasCollapseEffect()) {
                        $standardHeader.css('visibility', 'hidden');
                    }
                });
        }
    }

    $(window).resize(function() {
        if (H.isScreenWiderThan(800)) {
            displayStickyHeader();
        } else {
            clearStickyHeader();
        }
    });

    $(window).scroll(function() {
        if (H.isScreenWiderThan(800)) {
            // if the header is standard, we only care about where it become sticky;
            // if the header is sticky, we only care about where it become standard;
            if ($('header.sticky').length > 0) {
                hideStickyHeader();
            } else {
                displayStickyHeader();
            }
        }
    });

    $(document).ready(function() {
        // fix the error that youtube videos cover sticky header
        $('iframe[src*="youtube"]').each(function(){
            var ifrSource = $(this).attr('src');
            var wmode = "wmode=transparent";
            if(ifrSource.indexOf('?') !== -1) {
                $(this).attr('src', ifrSource + '&' + wmode);
            } else {
                $(this).attr('src', ifrSource + '?' + wmode);
            }
        });
    });
}(jQuery, themeHeader, window));

/*
 * dropdown menu effect
 */

(function($, H, window) {
    'use strict';

    var COLLAPSE_DURATION = 600;

    // depth first traverse to add z-index to submenus
    function addZIndex($submenuParent, depth) {
        $submenuParent.children('ul').css('z-index', depth * 50).children('li').each(function(index, el) {
            addZIndex($(el), depth + 1);
        });
    }

    function expandCurrentMenuItems() {
        $('.current_page_ancestor > .submenu-trigger > span.mi-expand-btn, .current-menu-ancestor > .submenu-trigger > span.mi-expand-btn, .current-menu-ancestor > .submenu-trigger-container > .submenu-trigger > span.mi-expand-btn, .lr-mi-with-widget-visible .current-menu-ancestor.mi-with-widget > .widget-area > .widget > h3 > .submenu-trigger > span.mi-expand-btn').each(function() {
            H.menuState.storeExpandedMenuTrigger($(this));
            if (!H.isScreenWiderThan(980)) {
                $(this).trigger('click');
            }
        });
    }

    function shouldAddExpandButton($menuItem) {
        var $widget;

        // if this menu item belongs to a widget, like custom menu/custom page/custom categories widget, then it's no need to add +/- icons
        if($menuItem.closest('.widget').length > 0) {
            return false;
        }

        if(!H.IS_MENU_ITEM_WITH_WIDGET_VISIBLE) {
            if($menuItem.hasClass('mega-magazine')) {
                return $('> ul.sub-menu > li.sub-menu-wrapper', $menuItem).length > 0;
            } else {
                return $menuItem.children("ul").length > 0 && $menuItem.children('ul.sub-menu, ul.children').children('li').not('.mi-with-widget').length > 0;
            }
        } else {
            if($menuItem.hasClass('mi-with-widget')) {
                $widget = $('> .widget-area > .widget', $menuItem);
                // just make sure that the widget has title and content area
                return $widget.children('h3').length > 0 && $widget.children('h3').nextAll('ul, div').length > 0;
            } else {
                return $menuItem.children("ul").length > 0;
            }
        }
    }

    function getSubmenuTrigger($menuItem) {
        var selectors = ['> .submenu-trigger', '> .submenu-trigger-container > .submenu-trigger', '> .widget-area > .widget > h3 > .submenu-trigger'];

        return $(selectors.join(','), $menuItem);
    }

    // get the next visible menu item, because sometimes the "with widget" menu item will not display
    function getNextVisibleMenuItem($menuItem) {
        var $next = $menuItem.next();

        if(!H.IS_MENU_ITEM_WITH_WIDGET_VISIBLE) {
            while($next.length > 0) {
                if($next.hasClass('mi-with-widget')) {
                    $next = $next.next();
                } else {
                    return $next;
                }
            }

            return $([]);
        } else {
            return $next;
        }
    }

    function expandSubmenuOnMobile($expandCollapseButton) {
        var $widget, 
            $menuItem = $expandCollapseButton.closest('li.menu-item, li.page_item');

        // change the styles before slide animation
        if($menuItem.hasClass('mega-magazine')) {
            // for magazine megamenu, open both of two lower-levels sub menu
            // set the submenu to visible before slide to fix an nonsmooth slide effect
            $menuItem.addClass('expanded').children('ul.sub-menu, ul.children').children('li.sub-menu-wrapper').addClass('expanded').children('ul.sub-menu').show().end().end().slideToggle(COLLAPSE_DURATION);

            getNextVisibleMenuItem($menuItem.children('ul.sub-menu, ul.children').children('li.sub-menu-wrapper')).addClass('next-to-expanded-menu-item');
        } else if ($menuItem.hasClass('mega-default')) {
            // for headline menu item which merge to the next level submenu, open both of two lower-levels sub menu
            // set the submenu to visible before slide to fix an nonsmooth slide effect
            $menuItem.addClass('expanded').children('ul.sub-menu, ul.children').children('li.mi-headline-merged').addClass('expanded').children('ul.sub-menu').show().end().end().slideToggle(COLLAPSE_DURATION);

            getNextVisibleMenuItem($menuItem.children('ul.sub-menu, ul.children').children('li.mi-headline-merged')).addClass('next-to-expanded-menu-item');
        } else if ($menuItem.hasClass('mi-with-widget')) {
            $widget = $expandCollapseButton.closest('.widget');
            // pay attention that sometimes there are many widgets in a widget area, need to use $expandCollapseButton to determine which one is the real target
            // and also need mark the current widget to "expanded" for display/hide the bottom line
            $menuItem.addClass('expanded');
            $widget.addClass('expanded').children('h3').next('ul, div').slideToggle(COLLAPSE_DURATION, function(){
                H.resizeGoogleMap($menuItem);
            });
        } else {
            $menuItem.addClass('expanded').children('ul.sub-menu, ul.children').slideToggle(COLLAPSE_DURATION);
        }

        

        getNextVisibleMenuItem($menuItem).addClass('next-to-expanded-menu-item');
    }

    function collapseSubmenuOnMobile($expandCollapseButton) {
        var $widget, 
            $menuItem = $expandCollapseButton.closest('li.menu-item, li.page_item');

        // change the styles after slide animation
        if($menuItem.hasClass('mega-magazine')) {
            // for magazine megamenu, open both of two lower-levels sub menu
            $menuItem.children('ul.sub-menu, ul.children').slideToggle(COLLAPSE_DURATION, function() {
                $menuItem.removeClass('expanded').children('ul.sub-menu, ul.children').children('li.sub-menu-wrapper').removeClass('expanded');
                getNextVisibleMenuItem($menuItem).removeClass('next-to-expanded-menu-item');
                getNextVisibleMenuItem($menuItem.children('ul.sub-menu, ul.children').children('li.sub-menu-wrapper')).removeClass('next-to-expanded-menu-item');

            });

        } else if ($menuItem.hasClass('mega-default')) {
            // for headline menu item which merge to the next level submenu, open both of two lower-levels sub menu
            $menuItem.children('ul.sub-menu, ul.children').slideToggle(COLLAPSE_DURATION, function(){
                $menuItem.removeClass('expanded').children('ul.sub-menu, ul.children').children('li.mi-headline-merged').removeClass('expanded');
                getNextVisibleMenuItem($menuItem).removeClass('next-to-expanded-menu-item');
                getNextVisibleMenuItem($menuItem.children('ul.sub-menu, ul.children').children('li.mi-headline-merged')).removeClass('next-to-expanded-menu-item');

            });

        } else if ($menuItem.hasClass('mi-with-widget')) {
            $widget = $expandCollapseButton.closest('.widget');
            // pay attention that sometimes there are many widgets in a widget area, need to determine which one is the real target
            $widget.children('h3').next('ul, div').slideToggle(COLLAPSE_DURATION, function(){
                $widget.removeClass('expanded');
                $menuItem.removeClass('expanded');
                getNextVisibleMenuItem($menuItem).removeClass('next-to-expanded-menu-item');
            });
        } else {
            $menuItem.children('ul.sub-menu, ul.children').slideToggle(COLLAPSE_DURATION, function(){
                $menuItem.removeClass('expanded');
                getNextVisibleMenuItem($menuItem).removeClass('next-to-expanded-menu-item');
            });
        }
    }

    function submenuTriggerButtonClickHandler(event) {
        if(H.isScreenWiderThan(980)) {
            return;
        }

        var $this = $(this);

        // do not propagate to <a>
        event.stopPropagation();
        event.preventDefault();

        if (!$this.hasClass("expanded")) {
            H.menuState.storeExpandedMenuTrigger($this);

            expandSubmenuOnMobile($this);
        } else {
            H.menuState.removeExpandedMenuTrigger($this);

            collapseSubmenuOnMobile($this);
        }

        $this.toggleClass('expanded');
    }

    function compensateDescriptionHeight($menu) {
        if(!H.isTopMenuItemsHasDescription($menu)) {
            return false;
        }

        $menu.children('li').each(function(index, el) {
            if(!$(this).hasClass('mi-with-description')) {
                $(this).addClass('description-height-compensate');
            }
        });
    }

    // use position of .dropdownmenu-mega to judge if the megamenu is activated (in large screen)
    function isMegamenuPlayground() {
        return $('.dropdownmenu-mega').css('position') === 'static';
    }

    function megaMenuDisplayHandler(callback) {
        if (isMegamenuPlayground()) {
            // reset the top position of mega menu
            H.setMegamenuTopPosition(this);

            H.displayTopLevelDropdownMenu($(this), callback);
        }
    }

    // add class span.submenu-trigger for <h3> in widget
    function wrapWidgetTitleInner() {
        $('.mi-with-widget .widget > h3').addClass('submenu-trigger-container').wrapInner('<span class="submenu-trigger"></span>');
    }

    // wrap the content of widget "work stream" to .widget-content
    function wrapWorkStreamWidgetContent() {
        $('header .widget_recent_projects_widget').each(function(index, el) {
            var $title = $(this).children('h3');

            if($(this).children('.widget-content').length === 0) {
                $(this).wrapInner('<div class="widget-content"></div>');
                $title.prependTo($(this));
            }
        });
    }

    function relocateNewsLetterWidgetTitle() {
        $('header .widget_newsletterwidget').each(function(index, el) {
            var $title = $(this).find('h3');

            $title.addClass('submenu-trigger-container').wrapInner('<span class="submenu-trigger"></span>').prependTo($(this));
        });
    }

    function wrapSearchWidgetContent() {
        $('header .widget_search').each(function(index, el) {
            var $title = $(this).children('h3');

            if($(this).children('.widget-content').length === 0) {
                $(this).wrapInner('<div class="widget-content"></div>');
                $title.prependTo($(this));
            }
        });
    }

    // change the news letter submit button to use fontawesome icons
    function changeNewsLetterWidgetSubmitButtonValue() {
        // need to change from Fontawesome ascii to unicode before set
        $('header .newsletter-submit').attr('value', '\uf0e0');
    }

    function getCurrentUrl() {
        var currentUrl = document.URL;
        return currentUrl.match(/\/$/)? currentUrl : currentUrl + '/';
    }

    // mark current menu items for widget and latest posts
    function markCurrentMenuItems() {
        var currentUrl = getCurrentUrl();

        $('.dropdownmenu-mega .widget, .dropdownmenu-mega .latest-posts').each(function(index, el) {
            if($('a[href="' + currentUrl + '"]', this).length > 0) {
                $('a[href="' + currentUrl + '"]', this).addClass('current-url');
                $(this).parents('li.menu-item').addClass('current-menu-ancestor');
            }
                       
        });
    }

    function changeWidgetsDOM() {
        wrapWidgetTitleInner();
        wrapWorkStreamWidgetContent();
        relocateNewsLetterWidgetTitle();
        wrapSearchWidgetContent();
        changeNewsLetterWidgetSubmitButtonValue();
        markCurrentMenuItems();
    }

    // add class "last-visible-menu-item" to remove the bottom border on < 980px, because sometimes the last menu item is "with widget" which will make this menu item hidden on < 980px
    function markLastVisibleMenuItem() {
        $('header ul.sub-menu').each(function(index, el) {
            var menuItems = $(this).children('li.menu-item');
            for(var i = menuItems.length-1; i >= 0; i--) {
                if(H.IS_MENU_ITEM_WITH_WIDGET_VISIBLE || (!H.IS_MENU_ITEM_WITH_WIDGET_VISIBLE && !$(menuItems[i]).hasClass('mi-with-widget'))) {
                    $(menuItems[i]).addClass('last-visible-menu-item');
                    return;
                }
            }
        });
    }

    // the very last submenu trigger and very last widgets in different levels should always has bottom border. But they need to be marked out to use CSS on them
    function markVeryLast() {
        var $lastWidget,
            $next = $('header .menu-container > ul > li:last-child');

        while($next.length > 0) {
            // mark the very last submenu-trigger at this level
            if($next.hasClass('mi-with-widget')) {
                $lastWidget = $('> .widget-area > .widget:last-child', $next).addClass('widget-very-last');

                $('> .submenu-trigger, > .submenu-trigger-container > .submenu-trigger', $lastWidget).addClass('submenu-trigger-very-last');
            } else {
                $('> .submenu-trigger, > .submenu-trigger-container > .submenu-trigger', $next).addClass('submenu-trigger-very-last');
            }

            // find the next level menu item, it should always be <li>
            $next = $('> ul.sub-menu > li.last-visible-menu-item', $next);
        }
    }

    // sometime there are more than one widgets in one menu item, need to mark it for e.g. display/hide the bottom line
    function markLastWidgetOfMenuItem() {
        $('li.menu-item.mi-with-widget').each(function(index, el) {
            $(this).children('.widget-area').children('.widget:last-child').addClass('last-widget');
        });
    }

    function compensateDescription() {
        // compensate height for menu items that doesn't have description
        compensateDescriptionHeight($('.top-navi > ul'));
        compensateDescriptionHeight($('.mainmenu > ul'));
    }

    // attach click handler for navigation buttons, include the search icon and cart icon
    function attachClickHandlerForMobileButtons() {
        $(".collapsed").click(function(event) {
            // menu buttons only work when screen < 980px
            if (!H.isScreenWiderThan(980)) {
                event.stopPropagation();

                H.collapsedMenuToggle($(this), function($menu) {
                    $menu.slideToggle(COLLAPSE_DURATION);
                });
            }
        });
    }

    // add "submenu expand button" for <li> which have sub menu
    function addSubmenuExpandCollapseButton() {
        $(".menu-container > ul li.menu-item, .menu-container > ul li.page_item").each(function() {
            var $this = $(this),
                $submenuTrigger = getSubmenuTrigger($this);

            if(shouldAddExpandButton($this)) {
                // add .mi-expand-btn with Javascript instead of PHP because can't change the source code of widget
                $("<span class='mi-expand-btn'></span>").appendTo($submenuTrigger).click(submenuTriggerButtonClickHandler);
            }
        });
    }

    // click not-link menu item will also expand/collapse the submenu
    function attachCollapseExpandEffectToNoLinkSubmenuTrigger() {
        $('span.submenu-trigger').click(function(event) {
            $(this).children('.mi-expand-btn').trigger('click');
        });
    }

    function addZIndexForAllSubmenus() {
        $(".menu-container > ul > li").each(function() {
            addZIndex($(this), 1);
        });
    }

    function initTapTrace() {
        $('li.dropdownmenu:has(.sub-menu), li.dropdownmenu li:has(.sub-menu), li.dropdownmenu:has(ul.children), li.dropdownmenu li:has(ul.children)').data('tap', 0);
    }

    function preventJumpForTapEmptyLink() {
        $(".menu-container > ul li > .submenu-trigger, .menu-container > ul li > .submenu-trigger-container > .submenu-trigger").on('click', function(event) {
            // propagate to <li>, but don't jump to the top of the page if href='#'
            if ($(this).attr('href') === '#') {
                event.preventDefault();
            }
        });
    }

    // "1st tap as hover, 2nd tap as click". if the second tap is on other place, we should erase the trace of the 1st tap
    function attachEraseFirstTapHandler() {
        var $menuItemsWithSubmenu = $('li.dropdownmenu:has(.sub-menu), li.dropdownmenu li:has(.sub-menu), li.dropdownmenu:has(ul.children), li.dropdownmenu li:has(ul.children)');

        $(document).on('touchend', function(event) {
            var $parents   = $(event.target).parents();

            $menuItemsWithSubmenu.each(function(index, el) {
                var $el = $(el),
                    shouldReset = true;

                for(var i = 0; i < $parents.length; i++) {
                    var parent = $parents[i];
                    if($el.get(0) === parent) {
                        shouldReset = false;
                    }
                }

                if(shouldReset) {
                    $el.data('tap', 0);

                    // also collpapse the expanded submenu
                    if($el.hasClass('submenu-expanded')) {
                        if($el.hasClass('dropdownmenu')) {
                            H.hideTopLevelDropdownMenu($(el));
                        } else {
                            H.hideDropdownMenu($(el));
                        }
                    }
                }
            });
        });
    }

    // check if the event target is the direct submenu trigger of a <li>
    function isDirectSubmenuTrigger($menuItem, $eventTarget) {
        return $eventTarget.closest('li.menu-item, li.page_item')[0] === $menuItem[0];
    }

    function throttleTapHandler(tapHandler) {
        return function(event) {
            if (H.isScreenWiderThan(980)) {
                // don't response to the tap action on the sub menu
                if(isDirectSubmenuTrigger($(this), $(event.target))) {
                    if ($(this).data('tap') === 0) {
                        event.preventDefault();

                        tapHandler.apply(this, [event]);

                        $(this).data('tap', 1);
                    } else {
                        $(this).data('tap', 0);
                    }
                }
            }
        };
    }

    function attachTapHandlerForTopMenuItem() {
        $('li.dropdownmenu:has(.sub-menu), li.dropdownmenu:has(ul.children)').on('touchend', throttleTapHandler(function(event){
            if($(this).hasClass('dropdownmenu-mega')) {
                megaMenuDisplayHandler.apply(this);
            } else {
                H.displayTopLevelDropdownMenu($(this));
            }
        }));
    }

    // at present, only the submenu of default dropdown menu will have the tap event handler. Adding tap event handler to megamenu should be carefully with the "submenu collpase" error.
    function attachTapHandlerForDeeperMenuItem() {
        $('.dropdownmenu-default li.menu-item:has(.sub-menu), .dropdownmenu-default li.page_item:has(ul.children)').on('touchend', throttleTapHandler(function(event){
            H.displayDropdownMenu($(this));
        }));
    }

    function attachHoverDelayHandlerForTopMenuItem() {
        $("li.mi-depth-0:not(.dropdownmenu)").hoverIntent({
            over: function() {
                H.collapseSearchShoppingMenuAnyway();
            },
            out: function() {},
            interval: H.HOVER_INTERVAL,
            timeout: H.HOVER_LEAVE_TIMEOUT
        });

        $("li.dropdownmenu-default").hoverIntent({
            over: function() {
                H.displayTopLevelDropdownMenu($(this));
            },
            out: function() {
                H.hideTopLevelDropdownMenu($(this));
            },
            interval: H.HOVER_INTERVAL,
            timeout: H.HOVER_LEAVE_TIMEOUT
        });

        $(".dropdownmenu-mega").hoverIntent({
            over: megaMenuDisplayHandler,
            out: function() {
                H.hideTopLevelDropdownMenu($(this));
            },
            interval: H.HOVER_INTERVAL,
            timeout: H.HOVER_LEAVE_TIMEOUT
        });
    }

    function attachHoverHandlerForTopMenuItem() {
        $("li.mi-depth-0:not(.dropdownmenu)").on({
            'mouseenter.topLevelMenuItem': function() {
                H.collapseSearchShoppingMenuAnyway();
            },
            'mouseleave.topLevelMenuItem': function() {}
        });

        $("li.dropdownmenu-default").on({
            'mouseenter.topLevelMenuItem': function() {
                H.displayTopLevelDropdownMenu($(this));
            },
            'mouseleave.topLevelMenuItem': function() { // seem this event sometimes not be fired
                H.hideTopLevelDropdownMenu($(this));
            }
        });

        $(".dropdownmenu-mega").on({
            'mouseenter.topLevelMenuItem': megaMenuDisplayHandler,
            'mouseleave.topLevelMenuItem': function() {
                H.hideTopLevelDropdownMenu($(this));
            }
        });
    }

    /* add slide effect for deeper level submenu, slide effect for first level has been added separately */
    function attachHoverHandlerForDeeperMenuItem() {
        $(".dropdownmenu-default li.menu-item, .dropdownmenu-default li.page_item").on({
            'mouseenter.menuItem': function() {
                if (H.isScreenWiderThan(980)) {
                    H.displayDropdownMenu($(this), function($this) {
                        // in many case, this is not necessary. But it can be used to collapse the expanded sub menu when hover on other menu items after sticky header slide out
                        $this.siblings('.submenu-expanded').triggerHandler('mouseleave');
                    });
                }
            },
            'mouseleave.menuItem': function() {
                if (H.isScreenWiderThan(980)) {
                    H.hideDropdownMenu($(this));
                }
            }
        });
    }

    function attachMenuItemHandler() {
        initTapTrace();

        preventJumpForTapEmptyLink();

        attachEraseFirstTapHandler();

        attachTapHandlerForTopMenuItem();
        // hover on the menu item will also close the search box and cart box, but with a little delay to avoid accidental hover
        if (H.isHoverDelayEnabled()) {
            attachHoverDelayHandlerForTopMenuItem();
        } else {
            attachHoverHandlerForTopMenuItem();
        }

        attachTapHandlerForDeeperMenuItem();
        attachHoverHandlerForDeeperMenuItem();
    }

    // click on sub menu will also trigger the click event of li.dropdownmenu-mega. So, disable it!
    function stopMegaSubmenuClickPropagation() {
        $(".dropdownmenu-mega > ul").click(function(event) {
            event.stopPropagation();
        });
    }

    function attachMenuItemHandlerForTouchDevice() {
        preventJumpForEmptyLink();

        stopMegaSubmenuClickPropagation();
    }

    function markCartNumberBoxToActive() {
        // add class "active" to cart icon if the cart has at least 1 product
        if (parseInt($(".cart-number-box").text()) > 0) {
            $(".cart-number-box").addClass("active");
        }
    }

    /* tap on the blank area of header also collapse the expanded nav/box */
    function collapseMobileMenuByTapOtherPlace() {
        $("header").on('touchend', function() {
            $(".collapsed.active").triggerHandler('click');
        });

        $('.collapsed, .menu-container > ul, .social > ul > li, .shopping-bag, .search-box, header .title a, .searchform').on('touchend', function(event) {
            event.stopPropagation();
        });
    }

    $(document).ready(function() {
        changeWidgetsDOM();

        markLastVisibleMenuItem();

        markVeryLast();

        markLastWidgetOfMenuItem();

        compensateDescription();

        attachClickHandlerForMobileButtons();

        addSubmenuExpandCollapseButton();

        attachCollapseExpandEffectToNoLinkSubmenuTrigger();

        addZIndexForAllSubmenus();

        markCartNumberBoxToActive();

        // wait a moment to let respond.js to complete the render (IE 8)
        setTimeout(expandCurrentMenuItems, 100);

        // attach event handler for menu items
        attachMenuItemHandler();

        // collapseMobileMenuByTapOtherPlace();

        // dirty job, operations when change the window size to > 980px or < 980px
        // do not use window.mediaQuery.addListener because it doesn't work well on iPad
        H.screenSwitchHandler(function(){
            // fix an weird error that the first menu item you hover on will not expand but collapse with JQuery 1.11 and IE8
            if(H.ie && H.ie === 8) {
                $('header').addClass('contains-small-screen-styles');
            }

            // remove the height of li.parent, created by megamenu
            $(".menu-container > ul li.menu-item-has-children, .menu-container > ul li.page_item_has_children").css('height', '');
            // remove the opacity of sub menu
            // remove the box styles of sub menu, created by slide toggle
            $(".menu-container > ul li.menu-item-has-children > ul, .menu-container > ul li.page_item_has_children > ul").css('opacity', '').css({
                'height': '',
                'margin-top': '',
                'margin-bottom': '',
                'padding-top': '',
                'padding-bottom': ''
            });

            // remove all of the styles added for megamenu
            $('.dropdownmenu-mega').each(function() {
                $(this).children('ul').css('top', '');
            });

            // TODO seems this line is no need now
            // remove the equal heights of megamenu columns
            $('.dropdownmenu-mega > ul.sub-menu > li.menu-item').css('height', '');
        }, function(){
            // fix an weird error that the first menu item you hover on will not expand but collapse with JQuery 1.11 and IE8
            // The reason seems because .css('display', '') is executed for sub menus when page load, so we need to change codes to execute this function only if resizing from small window
            if(H.ie && H.ie === 8) {
                // remove styles only if resize from small window
                if(!$('header').hasClass('contains-small-screen-styles')) {
                    return;
                } else {
                    $('header').removeClass('contains-small-screen-styles');
                }
            }
            
            // remove the hidden style for .sub-menu
            $(".menu-container > ul li.menu-item-has-children > ul, .menu-container > ul li.page_item_has_children > ul").css('display', '');

            // remove the "expanded" class from "plus icon" span
            $(".menu-container > ul li.page_item_has_children > .submenu-trigger > span.expanded, .menu-container > ul li.menu-item-has-children > .submenu-trigger > span.mi-expand-btn.expanded, .menu-container > ul li.menu-item-has-children > .submenu-trigger-container > .submenu-trigger > span.mi-expand-btn.expanded, .menu-container > ul .widget > h3 > .submenu-trigger > span.mi-expand-btn.expanded").removeClass('expanded');

            // remove the hidden/visible style added for widget content area
            $('.dropdownmenu-mega .widget > ul, .dropdownmenu-mega .widget > div').css('display', '');
        });
    });

    H.resizeQueue.push(function(){
        if (!H.isScreenWiderThan(980)) {
            // recover the menu state when resize the window to < 980px
            var menuTriggers = H.menuState.getExpandedMenuTriggers();
            for (var i = 0; i < menuTriggers.length; i++) {
                if (!menuTriggers[i].hasClass('active') && !menuTriggers[i].hasClass('expanded')) {
                    menuTriggers[i].triggerHandler('click');
                }
            }
        }
    }, 0);
}(jQuery, themeHeader, window));

/*
 * search/cart menu effect
 */
(function($, H, window) {
    'use strict';

    function compensateDescriptionHeight() {
        if(H.isTopMenuItemsHasDescription($('.mainmenu > ul'))) {
            $('.mobile-nav').addClass('description-height-compensate');
            $('.search-box').addClass('description-top-compensate');
            $('.shopping-bag').addClass('description-top-compensate');
        }
    }

    // define hover insensitive area for menu box. mouse leave menu box but enter these area will not trigger the mouseleave event
    function defineHoverInsensitiveAreaForMenuBox() {
        $('.search-box, .shopping-bag, .h1 .main-header, .h2 .mainmenu').hover(function() {
            H.menuState.setHoverOnMenuBoxInsensitiveArea(true);
        }, function() {
            H.menuState.setHoverOnMenuBoxInsensitiveArea(false);
        });
    }

    function attachHoverHandlerForSearchShoppingIcon(hoverIntentConfig) {
        // do not deal with the mouse leave event itself, deal with it on .main-header, .mainmenu, .search-box or shopping-bag
        $('.mobile-nav > .border').hoverIntent({
            over: function() {
                H.menuBoxDisplay($(this).children('a'));
            },
            out: function() {},
            interval: hoverIntentConfig.menuBoxHoverInterval,
            timeout: hoverIntentConfig.menuBoxLeaveTimeout
        });
    }

    function attachHoverHandlerForSearchShoppingMenuBox(hoverIntentConfig) {
        $('.search-box, .shopping-bag').hoverIntent({
            over: function() {},
            out: function() {
                if(!H.menuState.getHoverOnMenuBoxInsensitiveArea()) {
                    H.menuBoxHide($(this).data('trigger'));
                }
            },
            interval: hoverIntentConfig.menuBoxHoverInterval,
            timeout: hoverIntentConfig.menuBoxLeaveTimeout
        });
    }

    // if mouse re-enter .search-box and .shopping-bag, stop the collapse animation and expand them immidiately
    function attachMouseleaveHandlerForSearchShoppingMenuBox() {
        $('.search-box, .shopping-bag').on('mouseenter', function(){
            H.menuBoxDisplay($(this).data('trigger'));
        });
    }

    // hide expanded menu box when mouse leave "hover insensitive arear"
    function attachMouseleaveHandlerForHoverInsensitiveArea(hoverIntentConfig) {
        $('.h1 .main-header, .h2 .mainmenu').hoverIntent({
            over: function() {},
            out: function() {
                if(!H.menuState.getHoverOnMenuBoxInsensitiveArea()) {
                    H.expandedMenuBoxHide();
                }
            },
            interval: hoverIntentConfig.menuBoxHoverInterval,
            timeout: hoverIntentConfig.menuBoxLeaveTimeout
        });
    }

    function attachHoverHandlerForSearchAndShopping() {
        var hoverIntentConfig = getHoverIntentConfig();

        defineHoverInsensitiveAreaForMenuBox();

        attachHoverHandlerForSearchShoppingIcon(hoverIntentConfig);

        attachHoverHandlerForSearchShoppingMenuBox(hoverIntentConfig);

        attachMouseleaveHandlerForSearchShoppingMenuBox();

        attachMouseleaveHandlerForHoverInsensitiveArea(hoverIntentConfig);

        function getHoverIntentConfig() {
            var config = {};

            // even if hover delay is not enabled, we still delay for a short time to set isHoverOnMenuBoxInsensitiveArea at first
            config.menuBoxHoverInterval = 10;
            config.menuBoxLeaveTimeout = 10;

            if (H.isHoverDelayEnabled()) {
                config.menuBoxHoverInterval = H.HOVER_INTERVAL;
                config.menuBoxLeaveTimeout = H.HOVER_LEAVE_TIMEOUT;
            }

            return config;
        }
    }

    function attachTapHandlerForSearchAndShopping() {
        // bypass the hover event handler on <a>
        $('.mobile-nav > .border > a').on('touchend', function(event) {
            if (H.isScreenWiderThan(980)) {
                event.preventDefault();
            }
        });

        $(".mobile-nav > .border:has('a')").on('touchend', function(event) {
            if (H.isScreenWiderThan(980)) {
                var $menuTrigger = $(this).children('a');

                // bypass the hover event handler if have
                event.preventDefault();

                if (!$menuTrigger.hasClass('active')) {
                    H.menuBoxDisplay($menuTrigger);
                } else {
                    H.menuBoxHide($menuTrigger);
                }
            }
        });
    }

    function attachClickHandlerForSearchAndShopping() {
        $(".mobile-nav > .border:has('a')").on('click', function(event) {
            if (H.isScreenWiderThan(980)) {
                var $menuTrigger = $(this).children('a');

                if (!$menuTrigger.hasClass('active')) {
                    H.menuBoxDisplay($menuTrigger);
                } else {
                    H.menuBoxHide($menuTrigger);
                }
            }
        });
    }

    function collapseMenuBoxByClickingOtherPlace() {

        $(document).on('touchend click', function(event) {
            if(H.isScreenWiderThan(980) && !isClickOnInsensitiveArea(event)) {
                if(event.type === 'touchend') {
                    event.stopPropagation();
                }

                H.expandedMenuBoxHide();
            }
        });

        function isClickOnInsensitiveArea(event) {
            var $insensitiveAreas = $('.shopping-bag, .search-box, .mobile-nav'),
                $parents = $(event.target).parents(),
                result = false;

            $insensitiveAreas.each(function(index, el) {
                var $el = $(el);

                for(var i = 0; i < $parents.length; i++) {
                    var parent = $parents[i];
                    if($el.get(0) === parent) {
                        result = true;
                    }
                }
            });

            return result;
        }
    }

    $(document).ready(function() {
        compensateDescriptionHeight();

        // attach event handler for seach box and shopping bag
        if ($('.mobile-nav').hasClass('dropdown-on-hover-enabled')) {
            attachHoverHandlerForSearchAndShopping();
        } else {
            attachClickHandlerForSearchAndShopping()
        }

        attachTapHandlerForSearchAndShopping();

        collapseMenuBoxByClickingOtherPlace();

        // dirty job
        H.screenSwitchHandler(function(){
            // remove the opacity and the box styles of sub menu, created by slide toggle
            $(".search-box, .shopping-bag").css({
                'height': '',
                'margin-top': '',
                'margin-bottom': '',
                'padding-top': '',
                'padding-bottom': '',
                'opacity': ''
            });
        }, function() {

        });
    });

}(jQuery, themeHeader, window));