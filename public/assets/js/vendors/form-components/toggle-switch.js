/*\
|*| ========================================================================
|*| Bootstrap Toggle: bootstrap4-toggle.js v3.4.0
|*| https://gitbrent.github.io/bootstrap-toggle/
|*| ========================================================================
|*| Copyright 2018-2019 Brent Ely
|*| Licensed under MIT
|*| ========================================================================
\*/

$(document).ready(function () {
    var origin = window.location.href;
    var originBaseUrl = base_url;
    $("#showDropdown").focus(function () {
        $(".search-dropdown").addClass("show")
    })

    $('.ck-btn input:radio').click(function () {
        var selectedRadio = $(this).val();
        var addClassTxt = $(this).closest('.ck-btn').attr('id') + ' choose-btn';
        $('.choose-btn').removeClass('d-none').html(selectedRadio).attr('class', '').addClass(addClassTxt);
        //$('#showDropdown').attr("placeholder", "Search " + selectedRadio + '..');
        $('#showDropdown').attr("placeholder", searchPlaceHolders[selectedRadio]);
        $(".search-dropdown").removeClass("show");
        $(".search-dropdown").addClass("hide");
    })

    $('#showDropdown').bind('keyup', function (e) {
        var selectedRadio = $('.ck-btn input:radio:checked').val();
        $('#showDropdown').attr("placeholder", searchPlaceHolders[selectedRadio]);
        var search = $(this).val();
        var code = e.keyCode || e.which;
        if (search.length < 3) {
            $('.not-found').addClass('d-none');
            /* $('.choose-btn').addClass('d-none'); */
            $(".btn-and-tips").removeClass('d-none');
            //$('#showDropdown').attr("placeholder", "Please Select...");
            //$("input:radio[name=radio]:checked")[0].checked = false;
            /* $("input:radio[name=radio]:checked").each(function () {
                $(this).prop('checked', false);
            }); */
            $('.searching-data').addClass('d-none');
        } else {
            $.ajax({
                //url: origin + (origin.indexOf('/dashboard') != -1 ? '/../dashboard/serach-dashboard' : '/../../dashboard/serach-dashboard'),
                url: originBaseUrl + '/securefcbcontrol/dashboard/serach-dashboard',
                method: "POST",
                data: {
                    //"_token": $('#token').val(),
                    "_token": base_token,
                    "selectedRadio": selectedRadio ? selectedRadio : '',
                    "search": search
                },
                success: function (response) {
                    $('.searching-data').removeClass('d-none');
                    $(".searching-data ul").html('');
                    $(".btn-and-tips").addClass('d-none');
                    $('.serachValueNotFound').text("'" + search + "'");
                    $('.not-found').addClass('d-none');
                    var resultLenght = response.data.length;
                    var custfilter = new RegExp(search, "ig");
                    var repstr = "<span class='highlight'>" + search + "</span>";
                    if (selectedRadio == 'Artists') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/artists/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                var singleUrl = originBaseUrl + '/securefcbcontrol/artists/edit/' + item.id;
                                $(".searching-data ul").append("<li><a href='" + singleUrl + "'><span>" + item.firstname.replace(custfilter, repstr) + ' ' + item.lastname.replace(custfilter, repstr) + ' (' + item.email.replace(custfilter, repstr) + ")</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'Fans') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/fans/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                var singleUrl = originBaseUrl + '/securefcbcontrol/fans/edit/' + item.id;
                                $(".searching-data ul").append("<li><a href='" + singleUrl + "'><span>" + item.fullName.replace(custfilter, repstr) + ' (' + item.email.replace(custfilter, repstr) + ")</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'Songs') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/songs/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                $(".searching-data ul").append("<li><a data-search='" + item.name + "' data-type='" + selectedRadio + "' class='postableForm' href='javascript:void(0)'><span>" + item.name.replace(custfilter, repstr) + ' (' + item.fullName.replace(custfilter, repstr) + ")</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'Subscriptions') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/subscriptions/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                $(".searching-data ul").append("<li><a data-search='" + item.firstname + "' data-type='" + selectedRadio + "' class='postableForm' href='javascript:void(0)'><span>" + item.firstname.replace(custfilter, repstr) + ' (' + item.email.replace(custfilter, repstr) + ")" + ' (' + item.payment_id.replace(custfilter, repstr) + ")" + "</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'Transactions') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/transaction/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                var singleUrl = originBaseUrl + '/securefcbcontrol/transaction/index/' + item.id;
                                $(".searching-data ul").append("<li><a data-search='" + item.email + "' data-type='" + selectedRadio + "' class='postableForm' href='javascript:void(0)'><span>" + item.fullName.replace(custfilter, repstr) + ' (' + item.email.replace(custfilter, repstr) + ")" + ' (' + item.payment_id.toString().replace(custfilter, repstr) + ")" + "</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'CMS') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/cms-page/list';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                var singleUrl = originBaseUrl + '/securefcbcontrol/cms-page/edit/' + item.id;
                                $(".searching-data ul").append("<li><a href='" + singleUrl + "'><span>" + item.name.replace(custfilter, repstr) + ' (' + item.slug.replace(custfilter, repstr) + ")</span></a></li>");
                            });
                        }
                    } else if (selectedRadio == 'Forums') {
                        var listUrl = originBaseUrl + '/securefcbcontrol/forums/index';
                        //$('.results-column-header').html('<a href=' + listUrl + '>Show results in module(' + response.total + ')</a>');
                        $('.results-column-header').html("<a data-search='" + search + "' data-type='" + selectedRadio + "' class='postableFormListing' href='javascript:void(0)'>Show results in module(" + response.total + ")</a>");
                        $('#searchableFormListing').attr('action', listUrl);
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                $(".searching-data ul").append("<li><a data-search='" + item.post_topic + "' data-type='" + selectedRadio + "' class='postableForm' href='javascript:void(0)'><span>" + item.post_topic.replace(custfilter, repstr) + ' (' + item.fullName.replace(custfilter, repstr) + ")</span></a></li>");
                            });
                        }
                    } else {
                        $('.results-column-header').html('<a href="#">PENDING</a>');
                        if (resultLenght == 0) {
                            $(".searching-data").addClass('d-none');
                            $('.not-found').removeClass('d-none');
                        } else {
                            $.each(response.data, function (i, item) {
                                $(".searching-data ul").append("<li><a href='javascript:void(0)'><span>test</span></a></li>");
                            });
                        }
                    }

                }
            })

        }
    });

    $(document).on('click', '.postableForm', function () {
        var search = $(this).data('search');
        var type = $(this).data('type');
        var url = '';
        if (type == 'Transactions') {
            url = originBaseUrl + '/securefcbcontrol/transaction/index';
        } else if (type == 'Subscriptions') {
            url = originBaseUrl + '/securefcbcontrol/subscriptions/index';
        } else if (type == 'Songs') {
            url = originBaseUrl + '/securefcbcontrol/songs/index';
        } else if (type == 'Forums') {
            url = originBaseUrl + '/securefcbcontrol/forums/index';
        }
        var newForm = $('#searchableForm');
        newForm.attr('action', url);
        $('#searchableForm #searchableValue').val(search);
        newForm.submit();
    })

    $(document).on('click', '.postableFormListing', function () {
        var search = $(this).data('search');
        var type = $(this).data('type');
        var url = '';
        if (type == 'Artists') {
            url = originBaseUrl + '/securefcbcontrol/artists/index';
        }else if(type == 'Fans') {
            url = originBaseUrl + '/securefcbcontrol/fans/index';
        }else if(type == 'Songs') {
            url = originBaseUrl + '/securefcbcontrol/songs/index';
        }else if(type == 'Subscriptions') {
            url = originBaseUrl + '/securefcbcontrol/subscriptions/index';
        }else if(type == 'Transactions') {
            url = originBaseUrl + '/securefcbcontrol/transaction/index';
        }else if(type == 'CMS') {
            url = originBaseUrl + '/securefcbcontrol/cms-page/list';
        }else if(type == 'Forums') {
            url = originBaseUrl + '/securefcbcontrol/forums/index';
        }
        var newForm = $('#searchableFormListing');
        newForm.attr('action', url);
        $('#searchableValue').val(search);
        newForm.submit();
    })

    $('#searchableFormListing').submit(function () {
        var newForm = $('#searchableFormListing');
        if (newForm.attr('action') == '')
            return false;
    })

    /*  $('body').on('mouseup', function (e) {
         //$('body').trigger('keypress');
         var click= e.target.id;
         if(click != 'showDropdown'){
             $('body').trigger('keypress');
         }
     });
 
     $('body').bind('keypress', function (e) {
         var code = e.keyCode || e.which;
         if (code == 102) {
             $(".search-dropdown").removeClass("hide");
             $(".search-dropdown").addClass("show");
         }
         else
         {
             $(".search-dropdown").removeClass("show");
             $(".search-dropdown").addClass("hide");
         }
     }); */
    // $("#showDropdown").focusout(function(){
    //     $(".search-dropdown").removeClass("show")
    // })
})

    + function ($) {
        'use strict';

        // TOGGLE PUBLIC CLASS DEFINITION
        // ==============================

        var Toggle = function (element, options) {
            this.$element = $(element)
            this.options = $.extend({}, this.defaults(), options)
            this.render()
        }

        Toggle.VERSION = '3.4.0-beta'

        Toggle.DEFAULTS = {
            on: 'On',
            off: 'Off',
            onstyle: 'primary',
            offstyle: 'light',
            size: 'normal',
            style: '',
            width: null,
            height: null
        }

        Toggle.prototype.defaults = function () {
            return {
                on: this.$element.attr('data-on') || Toggle.DEFAULTS.on,
                off: this.$element.attr('data-off') || Toggle.DEFAULTS.off,
                onstyle: this.$element.attr('data-onstyle') || Toggle.DEFAULTS.onstyle,
                offstyle: this.$element.attr('data-offstyle') || Toggle.DEFAULTS.offstyle,
                size: this.$element.attr('data-size') || Toggle.DEFAULTS.size,
                style: this.$element.attr('data-style') || Toggle.DEFAULTS.style,
                width: this.$element.attr('data-width') || Toggle.DEFAULTS.width,
                height: this.$element.attr('data-height') || Toggle.DEFAULTS.height
            }
        }

        Toggle.prototype.render = function () {
            this._onstyle = 'btn-' + this.options.onstyle
            this._offstyle = 'btn-' + this.options.offstyle
            var size
                = this.options.size === 'large' || this.options.size === 'lg' ? 'btn-lg'
                    : this.options.size === 'small' || this.options.size === 'sm' ? 'btn-sm'
                        : this.options.size === 'mini' || this.options.size === 'xs' ? 'btn-xs'
                            : ''
            var $toggleOn = $('<label class="btn">').html(this.options.on)
                .addClass(this._onstyle + ' ' + size)
            var $toggleOff = $('<label class="btn">').html(this.options.off)
                .addClass(this._offstyle + ' ' + size)
            var $toggleHandle = $('<span class="toggle-handle btn btn-light">')
                .addClass(size)
            var $toggleGroup = $('<div class="toggle-group">')
                .append($toggleOn, $toggleOff, $toggleHandle)
            var $toggle = $('<div class="toggle btn" data-toggle="toggle">')
                .addClass(this.$element.prop('checked') ? this._onstyle : this._offstyle + ' off')
                .addClass(size).addClass(this.options.style)

            this.$element.wrap($toggle)
            $.extend(this, {
                $toggle: this.$element.parent(),
                $toggleOn: $toggleOn,
                $toggleOff: $toggleOff,
                $toggleGroup: $toggleGroup
            })
            this.$toggle.append($toggleGroup)

            var width = this.options.width || Math.max($toggleOn.outerWidth(), $toggleOff.outerWidth()) + ($toggleHandle.outerWidth() / 2)
            var height = this.options.height || Math.max($toggleOn.outerHeight(), $toggleOff.outerHeight())
            $toggleOn.addClass('toggle-on')
            $toggleOff.addClass('toggle-off')
            this.$toggle.css({ width: width, height: height })
            if (this.options.height) {
                $toggleOn.css('line-height', $toggleOn.height() + 'px')
                $toggleOff.css('line-height', $toggleOff.height() + 'px')
            }
            this.update(true)
            this.trigger(true)
        }

        Toggle.prototype.toggle = function () {
            if (this.$element.prop('checked')) this.off()
            else this.on()
        }

        Toggle.prototype.on = function (silent) {
            if (this.$element.prop('disabled')) return false
            this.$toggle.removeClass(this._offstyle + ' off').addClass(this._onstyle)
            this.$element.prop('checked', true)
            if (!silent) this.trigger()
        }

        Toggle.prototype.off = function (silent) {
            if (this.$element.prop('disabled')) return false
            this.$toggle.removeClass(this._onstyle).addClass(this._offstyle + ' off')
            this.$element.prop('checked', false)
            if (!silent) this.trigger()
        }

        Toggle.prototype.enable = function () {
            this.$toggle.removeAttr('disabled')
            this.$element.prop('disabled', false)
        }

        Toggle.prototype.disable = function () {
            this.$toggle.attr('disabled', 'disabled')
            this.$element.prop('disabled', true)
        }

        Toggle.prototype.update = function (silent) {
            if (this.$element.prop('disabled')) this.disable()
            else this.enable()
            if (this.$element.prop('checked')) this.on(silent)
            else this.off(silent)
        }

        Toggle.prototype.trigger = function (silent) {
            this.$element.off('change.bs.toggle')
            if (!silent) this.$element.change()
            this.$element.on('change.bs.toggle', $.proxy(function () {
                this.update()
            }, this))
        }

        Toggle.prototype.destroy = function () {
            this.$element.off('change.bs.toggle')
            this.$toggleGroup.remove()
            this.$element.removeData('bs.toggle')
            this.$element.unwrap()
        }

        // TOGGLE PLUGIN DEFINITION
        // ========================

        function Plugin(option) {
            return this.each(function () {
                var $this = $(this)
                var data = $this.data('bs.toggle')
                var options = typeof option == 'object' && option

                if (!data) $this.data('bs.toggle', (data = new Toggle(this, options)))
                if (typeof option == 'string' && data[option]) data[option]()
            })
        }

        var old = $.fn.bootstrapToggle

        $.fn.bootstrapToggle = Plugin
        $.fn.bootstrapToggle.Constructor = Toggle

        // TOGGLE NO CONFLICT
        // ==================

        $.fn.toggle.noConflict = function () {
            $.fn.bootstrapToggle = old
            return this
        }

        // TOGGLE DATA-API
        // ===============

        $(function () {
            $('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle()
        })

        $(document).on('touch.bs.toggle click.bs.toggle', 'div[data-toggle^=toggle]', function (e) {
            var $checkbox = $(this).find('input[type=checkbox]')
            $checkbox.bootstrapToggle('toggle')
            e.preventDefault()
        })

    }(jQuery);
