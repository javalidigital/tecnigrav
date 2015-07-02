(function($) {
    var converioInputSelector = "input, textarea, select";

    function serializeInput($optionBox) {
        var $inputs = $(converioInputSelector, $optionBox).not('.converio-menu-item-options-input');
        $inputs.each(function() {
            var name = $(this).data('fieldName');
            $(this).attr('name', name);
        });

        $('.converio-menu-item-options-input', $optionBox).val($inputs.serialize());
        $inputs.not('input[type="radio"]').not('select').removeAttr('name');
    }

    function getDepthClass($menuItem) {
        return $menuItem.attr('class').match(/menu-item-depth-\d+/)[0];
    }

    function getDepth($menuItem) {
        return parseInt(getDepthClass($menuItem).match(/\d+/)[0]);
    }

    function getParentMenuItem($menuItem) {
        var currentLevel = getDepthClass($menuItem).match(/\d+/)[0], shallowerLevel = currentLevel-1,
            shallowerLevelClass = 'menu-item-depth-' + shallowerLevel,
            $current;

        if(shallowerLevel >= 0) {
            for($current = $menuItem.prev(); $current.length > 0 && !$current.hasClass(shallowerLevelClass); $current = $current.prev()) {}

            return $current;
        } else {
            return $([]);
        }
    }

    function getTopMenuItem($menuItem) {
        var $prev = $current = $menuItem;
        for ($current = getParentMenuItemLi($prev); $current.length > 0; $prev = $current, $current = getParentMenuItemLi($prev)) {}

        return $prev;
    }

    function isMegamenuDefined($menuItem) {
        return getDepth($menuItem) === 0 && $menuItem.find('.edit-menu-item-converio-is-megamenu').is(':checked');
    }

    function isBelongsToMegamenu($menuItem) {
        var $current;

        for ($current = $menuItem; $current.length > 0; $current = getParentMenuItem($current)) {
            if(isMegamenuDefined($current)) {
                return true;
            }
        }

        return false;
    }

    function isMagazineMegamenuDefined($menuItem) {
        return getDepth($menuItem) === 0 && $menuItem.find('.edit-menu-item-converio-is-megamenu').is(':checked') && $menuItem.find('.edit-menu-item-converio-show-latest-posts').is(':checked');
    }

    function isBelongsToMagazineMegamenu($menuItem) {
        var $current;

        for ($current = $menuItem; $current.length > 0; $current = getParentMenuItem($current)) {
            if(isMagazineMegamenuDefined($current)) {
                return true;
            }
        }

        return false;
    }

    function megamenuChangeHandler($megamenuCheckbox) {
        if($megamenuCheckbox.length == 0) {
            return;
        }

        if($megamenuCheckbox.length > 1) {
            throw new Error('Too many elements. Only one checkbox should be passed.');
        }

        var $menuItem = $megamenuCheckbox.closest('li.menu-item'), 
        isMegaCheckboxChecked = $megamenuCheckbox.is(':checked');

        if(!$menuItem.hasClass('menu-item-depth-0')) {
            throw new Error('Wrong menu item element, it should has class "menu-item-depth-0".');
        }

        reverseCheckboxEffect($menuItem, isMegaCheckboxChecked);
        megamenuCollapseOptionBoxEffect($menuItem, isMegaCheckboxChecked);
        // also toggle the field "Reverse dropdown menu direction" of all sub menus
        submenuEffect($menuItem, isMegaCheckboxChecked);

        function reverseCheckboxEffect($menuItem, isMegaCheckboxChecked) {
            var $reverseCheckbox = $menuItem.find('.field-converio-reverse-direction');

            if(isMegaCheckboxChecked) {
                $reverseCheckbox.slideUp();
            } else {
                $reverseCheckbox.slideDown();
            }
        }

        function megamenuCollapseOptionBoxEffect($menuItem, isMegaCheckboxChecked) {
            var $megamenuCollapseOptionBox = $menuItem.find('.converio-menu-item-megamenu-collapse-option-box');

            if(isMegaCheckboxChecked) {
                $megamenuCollapseOptionBox.slideDown();
            } else {
                $megamenuCollapseOptionBox.slideUp();
            }
        }

        function submenuEffect($menuItem, isMegaCheckboxChecked) {
            var isMagazineMegaCheckboxChecked = $menuItem.find('.edit-menu-item-converio-show-latest-posts').is(':checked'),
                $current = $menuItem.next('li'),
                menuItemDepthClass = 'menu-item-depth-0';

            while($current.length > 0 && !$current.hasClass(menuItemDepthClass)) {

                if(isMegaCheckboxChecked) {
                    $('.field-converio-reverse-direction', $current).hide();
                } else {
                    $('.field-converio-reverse-direction', $current).show();
                }

                if(getDepth($current) === 1) {
                    if(isMegaCheckboxChecked && !isMagazineMegaCheckboxChecked) {
                        $('.field-converio-columns-headline-disabled', $current).show();
                    } else {
                        $('.field-converio-columns-headline-disabled', $current).hide();
                    }
                }

                if(getDepth($current) >= 1) {
                    if(isMegaCheckboxChecked) {
                        $('.field-converio-widget-area', $current).show();
                    } else {
                        $('.field-converio-widget-area', $current).hide();
                    }
                }
                
                $current = $current.next('li');
            }
        }
    }

    function depthChangeHandler(e, currentDepth, prevDepth) {
        if(currentDepth > 0) {
            $(this).find('.converio-menu-item-megamenu-option-box').hide();
        } else {
            $(this).find('.converio-menu-item-megamenu-option-box').show();
        }

        if(isBelongsToMegamenu($(this), currentDepth)) {
            $(this).find('.field-converio-reverse-direction').hide();
        } else {
            $(this).find('.field-converio-reverse-direction').show();
        }

        if(isBelongsToMegamenu($(this), currentDepth) && !isBelongsToMagazineMegamenu($(this), currentDepth) && currentDepth === 1) {
            $(this).find('.field-converio-columns-headline-disabled').show();
        } else {
            $(this).find('.field-converio-columns-headline-disabled').hide();
        }

        if(isBelongsToMegamenu($(this), currentDepth) && currentDepth >= 1) {
            $(this).find('.field-converio-widget-area').show();
        } else {
            $(this).find('.field-converio-widget-area').hide();
        }
    }

    function showPostsCheckboxChangeHandler($showPostsCheckbox) {
        if($showPostsCheckbox.length == 0) {
            return;
        }

        if($showPostsCheckbox.length > 1) {
            throw new Error('Too many elements. Only one checkbox should be passed.');
        }

        var $menuItem = $showPostsCheckbox.closest('li.menu-item');

        if(!$menuItem.hasClass('menu-item-depth-0')) {
            throw new Error('Wrong menu item element, it should has class "menu-item-depth-0".');
        }

        if($showPostsCheckbox.is(':checked')) {
            $menuItem.find('.field-converio-megamenu-columns').slideUp();
            $menuItem.find('.field-converio-show-author-info').slideDown();
        } else {
            $menuItem.find('.field-converio-megamenu-columns').slideDown();
            $menuItem.find('.field-converio-show-author-info').slideUp();
        }

        // display/hide checkboxes "don't display as columns headline in megamenu" in its sub menu items
        var $current = $menuItem.next('li'),
            menuItemDepthClass = 'menu-item-depth-0',
            isMegaCheckboxChecked = isMegamenuDefined($menuItem),
            isMagazineMegaCheckboxChecked = isMagazineMegamenuDefined($menuItem);

        while($current.length > 0 && !$current.hasClass(menuItemDepthClass)) {
            if(getDepth($current) === 1) {
                if(isMegaCheckboxChecked && !isMagazineMegaCheckboxChecked) {
                    $('.field-converio-columns-headline-disabled', $current).show();
                } else {
                    $('.field-converio-columns-headline-disabled', $current).hide();
                }
            }

            $current = $current.next('li');
        }
    }

    $(document).ready(function() {
        // override updateDepthClass to provide hook for depth change
        $.fn.extend({
            updateDepthClass : function(current, prev) {
                return this.each(function(){
                    var t = $(this);
                    prev = prev || t.menuItemDepth();
                    $(this).removeClass('menu-item-depth-'+ prev )
                        .addClass('menu-item-depth-'+ current );

                    if($(this).hasClass('menu-item')) {
                        $(this).trigger('depthChange', [current, prev]);
                    }
                });
            }
        });

        if(typeof wpNavMenu != 'undefined'){
            wpNavMenu.addItemToMenu = function(menuItem, processMethod, callback) {
                var menu = $('#menu').val(),
                    nonce = $('#menu-settings-column-nonce').val(),
                    params;

                processMethod = processMethod || function(){};
                callback = callback || function(){};

                params = {
                    'action': 'add-menu-item',
                    'menu': menu,
                    'menu-settings-column-nonce': nonce,
                    'menu-item': menuItem
                };

                $.post( ajaxurl, params, function(menuMarkup) {
                    var ins = $('#menu-instructions');

                    menuMarkup = $.trim( menuMarkup ); // Trim leading whitespaces
                    processMethod(menuMarkup, params);

                    $( 'li.pending' ).not('.megamenu-checkbox-handler-bound').addClass('megamenu-checkbox-handler-bound')
                    .on('depthChange', depthChangeHandler)
                    .find('.edit-menu-item-converio-is-megamenu').on('change', function() {
                        megamenuChangeHandler($(this));
                    }).end()
                    .find('.edit-menu-item-converio-show-latest-posts').on('change', function(){
                        showPostsCheckboxChangeHandler($(this));
                    }).end()
                    .find('.converio-menu-item-option-box').on('change', converioInputSelector, function(){
                        serializeInput($(this).closest('.converio-menu-item-option-box'));
                    });

                    // Make it stand out a bit more visually, by adding a fadeIn
                    $( 'li.pending' ).hide().fadeIn('slow');
                    $( '.drag-instructions' ).show();
                    if( ! ins.hasClass( 'menu-instructions-inactive' ) && ins.siblings().length )
                        ins.addClass( 'menu-instructions-inactive' );

                    callback();
                });
            };
        }

    	$('.converio-menu-item-option-box').each(function(index, el) {
    		serializeInput($(el));
    	});

    	$('.converio-menu-item-option-box').on('change', converioInputSelector, function(){
    		serializeInput($(this).closest('.converio-menu-item-option-box'));
    	});

        $('.edit-menu-item-converio-is-megamenu').on('change', function() {
            megamenuChangeHandler($(this));
        });

        $('.edit-menu-item-converio-show-latest-posts').on('change', function() {
            showPostsCheckboxChangeHandler($(this));
        });

        // after dom load, expand megamenu box and hide field "Reverse dropdown menu direction" if field "is_megamenu" is checked
        $('.menu-item-depth-0 .edit-menu-item-converio-is-megamenu:checked').each(function() {
            megamenuChangeHandler($(this));
        });

        // after dom load, expand "show author info" if field "show latest posts" is checked
        $('.menu-item-depth-0 .edit-menu-item-converio-show-latest-posts:checked').each(function() {
            showPostsCheckboxChangeHandler($(this));
        });

        $('.menu-item').on('depthChange', depthChangeHandler);

    });
}(jQuery));