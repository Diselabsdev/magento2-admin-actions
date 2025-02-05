define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
], function ($, confirm, $t) {
    'use strict';

    return function (config) {
        return {
            init: function () {
                this.bindEvents();
                this.initializeSessionCheck();
            },

            bindEvents: function () {
                var self = this;

                // Terminate all sessions button
                $(document).on('click', '[data-role="terminate-all-sessions"]', function (e) {
                    e.preventDefault();
                    self.confirmTerminateAll();
                });

                // Individual session termination
                $(document).on('click', '[data-role="terminate-single-session"]', function (e) {
                    e.preventDefault();
                    var sessionId = $(this).data('session-id');
                    self.confirmTerminateSingle(sessionId);
                });
            },

            initializeSessionCheck: function () {
                var self = this;
                // Check session status every minute
                setInterval(function () {
                    self.checkSessionStatus();
                }, 60000);
            },

            checkSessionStatus: function () {
                $.ajax({
                    url: config.checkSessionUrl,
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.valid) {
                            window.location.href = config.loginUrl;
                        }
                    }
                });
            },

            confirmTerminateAll: function () {
                var self = this;
                confirm({
                    title: $t('Terminate All Sessions'),
                    content: $t('Are you sure you want to terminate all active sessions? This will log out all administrators, including yourself.'),
                    actions: {
                        confirm: function () {
                            self.terminateAllSessions();
                        }
                    }
                });
            },

            confirmTerminateSingle: function (sessionId) {
                var self = this;
                confirm({
                    title: $t('Terminate Session'),
                    content: $t('Are you sure you want to terminate this session?'),
                    actions: {
                        confirm: function () {
                            self.terminateSession(sessionId);
                        }
                    }
                });
            },

            terminateAllSessions: function () {
                $.ajax({
                    url: config.terminateAllUrl,
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            window.location.href = config.loginUrl;
                        }
                    }
                });
            },

            terminateSession: function (sessionId) {
                $.ajax({
                    url: config.terminateSessionUrl,
                    data: {
                        session_id: sessionId
                    },
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            }
        };
    };
});
