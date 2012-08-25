(function($) {
    "use strict";
    var Message = function() {
        this.element = $('body');
        this.toolbar = $('div.btn-toolbar');
        this.mailboxContainer = $('div.msi_message_mailbox_container');
        this.messageContainer = $('div.msi_message_message_container');
        this.ajaxLoader = 'url(/bundles/msimessage/img/ajax-loader.gif) center center no-repeat';
        this.currentMailbox = '';
        this.currentMessage = 0;


        this.listen();
        this.init();
    }

    Message.prototype = {
        listen: function() {
            var self = this;
            this.element.on('click', 'a.msi_message_load_message', function() {
                self.loadMessage(this.hash);
            });
            this.toolbar.on('click', 'a.msi_message_load_mailbox', function() {
                self.loadMailbox(this.hash);
            });
        },

        init: function() {
            var self = this;
            if (location.hash) {
                self.loadMailbox(location.hash, function() {
                    self.loadMessage(location.hash);
                });
            }
        },

        loadMailbox: function(hash, callback) {
            var self = this;
            var params = self.cleanHash(hash);
            var name = params[0];

            if (false === self.mailboxIsValid(name)) {
                return;
            }

            // remove old message
            self.messageContainer.empty();
            // set current mailbox
            self.currentMailbox = name;
            // ajax call
            self.get(self.mailboxContainer, location.pathname+name, callback);
        },

        loadMessage: function(hash, callback) {
            var self = this;
            var params = self.cleanHash(hash);
            var id = params[1];

            if (false === self.messageIsValid(id)) {
                return;
            }

            // remove not read icon
            self.mailboxContainer.find('i#asterisk'+id).remove();
            // set current message
            self.currentMessage = id;
            // ajax call
            self.get(self.messageContainer, location.pathname+'show?id='+id, callback);
        },

        get: function(container, url, callback) {
            var self = this;
            container.fadeOut(0, function() {
                container.parent().css('background', self.ajaxLoader);
                $.ajax(url, {
                    success: function(response) {
                        container.html(response);
                        container.parent().css('background', 'none');
                        container.fadeIn(0);
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                });
            });
        },

        mailboxIsValid: function(name) {
            if (typeof name !== 'string') {
                return false;
            }

            if (-1 === $.inArray(name, ['inbox', 'sent', 'trash'])) {
                return false;
            }

            if (this.currentMailbox === name) {
                return false;
            }

            return true;
        },

        messageIsValid: function(id) {
            if (typeof id !== 'number') {
                return false;
            }

            if (this.currentMessage === id) {
                return false;
            }

            return true;
        },

        cleanHash: function(hash) {
            var params = hash.replace('#', '').split('/');
            if (typeof params[1] === 'string') {
                params[1] = parseInt(params[1]);
            }
            return params;
        },
    }

    var message = new Message();
})(jQuery);
