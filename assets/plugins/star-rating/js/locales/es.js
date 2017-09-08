/*!
 * Star Rating Spanish Translations
 *
 * This file must be loaded after 'star-rating.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 *
 * @see http://github.com/kartik-v/bootstrap-star-rating
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
(function ($) {
    "use strict";
    $.fn.ratingLocales.es = {
        defaultCaption: '{rating} Estrellas',
        starCaptions: function(val){
            return val + ' Estrellas';
        },
        clearButtonTitle: 'Limpiar',
        clearCaption: 'Sin Calificar'
    };
})(window.jQuery);