define([
    'ko',
    'jquery',
    'weltpixel_quickview',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'magnificPopup'
], function (ko, $, weltpixel_quickview, Component, customerData, magnificPopup) {
    'use strict';

    return Component.extend({
        /** @inheritdoc */
        initialize: function () {
            var that  = this;
            this._super();
            this.wpConfirmationPopup = customerData.get('wp_confirmation_popup');
            this.messages = customerData.get('messages');
            this.productAddedEvent = ko.computed(function()  {
               return [ that.wpConfirmationPopup(), that.messages() ];
            });

            this.productAddedEvent.subscribe(function(options) {
                let wpConfirmationPopupOptions = options[0];
                let messagesOptions = options[1];
                let parentBody = window.parent.document.body;

                if (wpConfirmationPopupOptions.confirmation_popup_content && messagesOptions.wp_messages) {
                    let quickviewPopup = $('.wp-quickview-popup .mfp-close', parentBody);
                    if (quickviewPopup.length) {
                        let parentJQuery = window.parent.jQuery;
                        setTimeout(function() {
                            $('.wp-quickview-popup .mfp-close', parentBody).trigger('click');
                            parentJQuery.magnificPopup.open({
                                items: {
                                    src: wpConfirmationPopupOptions.confirmation_popup_content,
                                    type: 'inline'
                                }
                            });
                        }, 1000);
                    } else {
                        $.magnificPopup.open({
                            items: {
                                src: wpConfirmationPopupOptions.confirmation_popup_content,
                                type: 'inline'
                            },
                            mainClass: 'mfp-wp-confirmation-popup'
                        });
                    }

                }
            });
        }
    });
});
