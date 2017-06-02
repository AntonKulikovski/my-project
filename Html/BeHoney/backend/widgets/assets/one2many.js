/* jQuery plugin for One2ManyWidget */
(function ($) {
    var consts = {
        TEMPLATE_KEY_PLACEHOLDER: '___KEY_{widgetId}__PLACEHOLDER___',
        CSS_CLASS_MODEL_WRAPPER: 'one2many-model',
        CSS_CLASS_MODELS_WRAPPER: 'one2many-models',
        CSS_CLASS_MODEL_REMOVE: 'one2many-model-remove',
        CSS_CLASS_MODEL_ADD: 'one2many-add-model',
        NEW_MODEL_KEY_PREFIX: 'new_'
    };

    $.fn.flexibuildOne2Many = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.flexibuildOne2Many');
            return false;
        }
    };
    $.fn.flexibuildOne2Many.consts = consts;

    var defaults = {
        lastId: 0,
        formName: '',
        sortable: false,
        sortableOptions: {},
        template: null,
        nestedParentId: null,
        nestedModelKey: null,
        nestedAdditionalFormNamePart: null,
        slideUpOnRemove: true,
        slideDownOnAdd: true,
        attributes: []
    };

    var widgetDatas = {};

    var widgetEvents = {
        beforeAdd: 'beforeAdd',
        afterAdd: 'afterAdd',
        beforeRemove: 'beforeRemove',
        afterRemove: 'afterRemove'
    };
    $.fn.flexibuildOne2Many.eventTypes = widgetEvents;

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $widget = $(this),
                    id = $widget.attr('id'),
                    initOptions = $.extend({}, options);

                var settings = $.extend({}, defaults, options || {});

                if (settings.template === null) {
                    switch (true) {
                        case settings.nestedParentId === null: // no break
                        case settings.nestedModelKey === null: // no break
                        case settings.nestedAdditionalFormNamePart === null: // no break
                            return $.error('If setting template is not set, there are settings ' + [
                                '"nestedParentId"',
                                '"nestedModelKey"',
                                '"nestedAdditionalFormNamePart"'
                            ].join(', ') + ' are required.');
                    }
                }

                widgetDatas[id] = {
                    settings: settings,
                    initOptions: initOptions
                };

                if (settings.template === null) {
                    initFromNestedParent(id);
                }

                var addSelector = '.' + consts.CSS_CLASS_MODEL_ADD + '__' + id;
                $widget.off('click.flexibuildOne2Many', addSelector).on('click.flexibuildOne2Many', addSelector, function () {
                    methods.add.call($widget, $(this));
                    return false;
                });

                var removeSelector = '.' + consts.CSS_CLASS_MODEL_REMOVE + '__' + id;
                $widget.off('click.flexibuildOne2Many', removeSelector).on('click.flexibuildOne2Many', removeSelector, function () {
                    methods.remove.call($widget, $(this), false);
                    return false;
                });

                if (settings.sortable) {
                    var sortableOptions = $.extend({}, settings.sortableOptions, {
                        items: '.' + consts.CSS_CLASS_MODEL_WRAPPER + '__' + id
                    });
                    $widget.find('.' + consts.CSS_CLASS_MODELS_WRAPPER + '__' + id).sortable(sortableOptions);
                }
            });
        },

        add: function ($clickedBtn) {
            var $widget = $(this),
                id = $widget.attr('id'),
                slideDownOnAdd = widgetDatas[id].settings.slideDownOnAdd,
                newKey = consts.NEW_MODEL_KEY_PREFIX + ++widgetDatas[id].settings.lastId,
                keyPlaceholder = getKeyPlaceholder(id),

                template = widgetDatas[id].settings.template,
                content = replaceAll(template, keyPlaceholder, newKey),
                $modelsWrapper = $widget.find('.' + consts.CSS_CLASS_MODELS_WRAPPER + '__' + id),

                newFormAttributes = (function () {
                    var attributes = widgetDatas[id].settings.attributes,
                        result = [];
                    $.each(attributes, function (i) {
                        var attribute = {};
                        $.each(attributes[i], function (key, value) {
                            attribute[key] = isString(value) ? replaceAll(value, keyPlaceholder, newKey) : value;
                        });
                        result.push(attribute);
                    });
                    return result;
                })();

            var event = $.Event(widgetEvents.beforeAdd);
            event.newKey = newKey;
            event.newContent = content;
            event.newFormAttributes = newFormAttributes;
            event.clickedBtn = $clickedBtn || null;
            $widget.trigger(event);
            if (event.result === false) {
                return false;
            }

            newKey = event.newKey;
            content = event.newContent;
            newFormAttributes = event.newFormAttributes;
            var processAttributes = function () {
                var $form = $widget.closest('form');
                $.each(newFormAttributes, function (i) {
                    $form.yiiActiveForm('add', newFormAttributes[i]);
                });
                $form.yiiActiveForm('validateAttribute', id);
            };

            if (slideDownOnAdd) {
                slideDownOnAdd = slideDownOnAdd === true ? {} : slideDownOnAdd;
                $modelsWrapper.append($(content).hide());
                $modelsWrapper
                    .find('.' + consts.CSS_CLASS_MODEL_WRAPPER + '__' + id + '[data-key="' + newKey + '"]')
                    .slideDown(slideDownOnAdd);
            } else {
                $modelsWrapper.append(content);
            }
            processAttributes();

            var event = $.Event(widgetEvents.afterAdd);
            event.newKey = newKey;
            event.newContent = content;
            event.newFormAttributes = newFormAttributes;
            event.insertedModel = $modelsWrapper.find('.' + consts.CSS_CLASS_MODEL_WRAPPER + '__' + id + '[data-key="' + newKey + '"]');
            event.clickedBtn = $clickedBtn || null;
            $widget.trigger(event);
            return true;
        },

        remove: function (item, itemIsKey) {
            var $widget = $(this),
                id = $widget.attr('id'),
                slideUpOnRemove = widgetDatas[id].settings.slideUpOnRemove,
                wrapperClassSelector = '.' + consts.CSS_CLASS_MODEL_WRAPPER + '__' + id,
                $modelWrapper, event;
            if (itemIsKey) {
                $modelWrapper = $widget.find(
                    ('.' + consts.CSS_CLASS_MODELS_WRAPPER + '__' + id) + ' ' +
                    (wrapperClassSelector + '[data-key="' + item + '"]')
                );
            } else if ($(item).is(wrapperClassSelector)) {
                $modelWrapper = $(item);
            } else {
                $modelWrapper = $(item).closest(wrapperClassSelector);
            }

            if (!$modelWrapper.length) {
                $.error('Cannot find item that must be removed.');
                return false;
            }
            var key = $modelWrapper.data('key');

            var attributeIdsToRemove = (function () {
                var attributes = widgetDatas[id].settings.attributes,
                    keyPlaceholder = getKeyPlaceholder(id),
                    result = [];
                $.each(attributes, function (i) {
                    var attributeId = attributes[i].id;
                    if (isString(attributeId)) {
                        attributeId = replaceAll(attributeId, keyPlaceholder, key);
                    }
                    result.push(attributeId);
                });
                return result;
            })();

            event = $.Event(widgetEvents.beforeRemove);
            event.itemToRemove = $modelWrapper;
            event.itemKey = key;
            event.formAttributesIdsToRemove = attributeIdsToRemove;
            $widget.trigger(event);
            if (event.result === false) {
                return false;
            }

            $modelWrapper = event.itemToRemove;
            key = event.itemKey;
            attributeIdsToRemove = event.formAttributesIdsToRemove;

            var remove = function () {
                var $form = $widget.closest('form');
                $modelWrapper.remove();
                $.each(attributeIdsToRemove, function (i) {
                    $form.yiiActiveForm('remove', attributeIdsToRemove[i]);
                });
                $form.yiiActiveForm('validateAttribute', id);

                event = $.Event(widgetEvents.afterRemove);
                event.itemKey = key;
                event.removedContent = $('<div />').append($modelWrapper).html();
                event.removedFormAttributesIds = attributeIdsToRemove;
                $widget.trigger(event);
            };
            if (slideUpOnRemove) {
                slideUpOnRemove = slideUpOnRemove === true ? {} : slideUpOnRemove;
                slideUpOnRemove.complete = remove;
                $modelWrapper.slideUp(slideUpOnRemove);
            } else {
                remove();
            }

            return true;
        },

        destroy: function () {
            return this.each(function () {
                var $widget = $(this),
                    id = $widget.attr('id');
                $widget.off('.flexibuildOne2Many');
                delete widgetDatas[id];
            });
        },

        data: function () {
            var id = $(this).attr('id');
            return widgetDatas[id];
        }
    };

    var isString = function (value) {
        var result = (typeof value === 'string') || (value && (value instanceof String));
        return !!result;
    };

    var escapeRegExp = function (str) {
        return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, '\\$1');
    };

    var replaceAll = function (str, from, to) {
        if (!isString(str)) {
            return '';
        } else if (!str.length) {
            return str;
        }
        var exp = new RegExp(escapeRegExp('' + from + ''), 'gi');
        return str.replace(exp, ('' + to + '').replace(/\$/g, '$$$$'));
    };

    var initFromNestedParent = function (id) {
        var template = null,
            nestedParentId = widgetDatas[id].settings.nestedParentId,
            nestedModelKey = widgetDatas[id].settings.nestedModelKey,
            nestedAdditionalFormNamePart = widgetDatas[id].settings.nestedAdditionalFormNamePart;

        if (!widgetDatas[nestedParentId]) {
            return $.error([
                'Widget with id "' + nestedParentId + '" does not exist.',
                'Cannot init widget #"' + id + '".'
            ].join(' '));
        }

        var currentFormName = [
            replaceAll( // normalized parent form name
                widgetDatas[nestedParentId].settings.formName,
                getKeyPlaceholder(nestedParentId),
                nestedModelKey
            ),
            nestedAdditionalFormNamePart // current form name
        ].join('');

        template = widgetDatas[nestedParentId].settings.template;
        template = replaceAll(template, nestedParentId, id);
        template = replaceAll(
            template,
            replaceAll( // because placeholder changed in previous `replaceAll()`
                widgetDatas[nestedParentId].settings.formName,
                nestedParentId,
                id
            ),
            currentFormName
        );

        var nestedParentAttributes = widgetDatas[nestedParentId].settings.attributes,
            currentAttributes = [];
        $.each(nestedParentAttributes, function (i) {
            var attribute = {};
            $.each(nestedParentAttributes[i], function (key, value) {
                attribute[key] = isString(value) ? replaceAll(value, nestedParentId, id) : value;
            });
            currentAttributes.push(attribute);
        });

        widgetDatas[id].settings.formName = currentFormName;
        widgetDatas[id].settings.template = template;
        widgetDatas[id].settings.attributes = currentAttributes;

        return template;
    };

    var getKeyPlaceholder = function (id) {
        return replaceAll(consts.TEMPLATE_KEY_PLACEHOLDER, '{widgetId}', id);
    };
})(window.jQuery);
