define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, alert, $t) {
    'use strict';

    return function (config) {
        return {
            refreshInterval: 30000, // 30 seconds
            lastActivityId: 0,
            
            init: function () {
                this.initializePolling();
                this.bindEvents();
            },

            initializePolling: function () {
                var self = this;
                setInterval(function () {
                    self.checkNewActivity();
                }, this.refreshInterval);
            },

            bindEvents: function () {
                var self = this;
                
                // Terminate session button handler
                $(document).on('click', '[data-role="terminate-session"]', function (e) {
                    e.preventDefault();
                    var sessionId = $(this).data('session-id');
                    self.terminateSession(sessionId);
                });

                // Restore action button handler
                $(document).on('click', '[data-role="restore-action"]', function (e) {
                    e.preventDefault();
                    var actionId = $(this).data('action-id');
                    self.restoreAction(actionId);
                });
            },

            checkNewActivity: function () {
                var self = this;
                $.ajax({
                    url: config.checkActivityUrl,
                    data: {
                        last_id: this.lastActivityId
                    },
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.activities && response.activities.length) {
                            self.processNewActivities(response.activities);
                        }
                    }
                });
            },

            processNewActivities: function (activities) {
                var self = this;
                activities.forEach(function (activity) {
                    if (activity.id > self.lastActivityId) {
                        self.lastActivityId = activity.id;
                    }
                    
                    if (activity.is_suspicious) {
                        self.showSuspiciousActivityAlert(activity);
                    }
                    
                    self.updateActivityGrid(activity);
                });
            },

            showSuspiciousActivityAlert: function (activity) {
                alert({
                    title: $t('Suspicious Activity Detected'),
                    content: this.formatSuspiciousActivity(activity)
                });
            },

            formatSuspiciousActivity: function (activity) {
                return $t('Type: %1<br>User: %2<br>IP: %3<br>Time: %4')
                    .replace('%1', activity.type)
                    .replace('%2', activity.username)
                    .replace('%3', activity.ip_address)
                    .replace('%4', activity.created_at);
            },

            updateActivityGrid: function (activity) {
                // If grid exists, prepend new activity
                var $grid = $('#admin-actions-grid');
                if ($grid.length) {
                    var rowHtml = this.formatActivityRow(activity);
                    $grid.find('tbody').prepend(rowHtml);
                }
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
                        } else {
                            alert({
                                title: $t('Error'),
                                content: response.message
                            });
                        }
                    }
                });
            },

            restoreAction: function (actionId) {
                $.ajax({
                    url: config.restoreActionUrl,
                    data: {
                        action_id: actionId
                    },
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert({
                                title: $t('Error'),
                                content: response.message
                            });
                        }
                    }
                });
            }
        };
    };
});
