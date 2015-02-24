(function($){
    'use strict';
    
    // Check if exist auth token
    var _checkAuthToken = function() {
        return $.cookie('authToken') || false;
    }; 
    
    // Is logged in?
    var _isloggedIn = function() {
        return !!_checkAuthToken();
    };
    
    // Logout
    jQuery.fn.extend({
        // Logout
        logout: function() {
            return this.on('click', function(event) {
                if($.removeCookie('authToken') && $.removeCookie('profile')) {
                    init();
                    $('#menu').html('');
                }
                event.preventDefault();
            });
        },
        // Login
        login: function() {
            var self = this;
            return self.submit(function(event) {
                if (self.find('#email').val() === '') {
                    self.find('#errors').text('Please enter a valid email');
                    return;
                } else if (self.find('#password').val() === '') {
                    self.find('#errors').text('Please enter a valid password');
                    return;
                }
                
                var $btn = self.find('#signin').button('loading');
                
                $.ajax({
                    type: 'POST',
                    url: '/auth/login',
                    data: {
                        email: self.find('#email').val(),
                        password: self.find('#password').val()
                    },
                    cache: false
                })
                .done(function(data) {
                    data = JSON.parse(data); 
                    
                    $.cookie('authToken', data.value.authToken, { expires: 1 });
                    $.cookie('profile', {
                        id: data.value.accountID,
                        email: data.value.email
                    }, { expires: 1 });
                    
                    $btn.button('reset');
                    self.find('#errors').text('');
                    init();
                })
                .fail(function(data) {
                    if(data.status === 404) {
                        self.find('#errors').text('Email or password not found. Try again.');
                    } else {
                        data = JSON.parse(data.responseText);
                        self.find('#errors').text(data.message);
                    }
                    $btn.button('reset');
                });
                
                event.preventDefault();
            });
        },
        // Get all transactions for this user
        loadTransactions: function(authToken, cb) {
            var self = this;
            $.ajax({
                url: '/transactions/viewall',
                type: 'POST',
                data: {
                    authToken: authToken
                },
                cache: false
            })
            .done(function(html) {
                self.html(html);
                
                if(typeof cb === 'function') {
                    cb(self);
                }
            });
        },
        // Create new transaction for this user
        addTransaction: function(authToken) {
            var self = this;
            
            self.find('#created').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            
            return self.submit(function(event) {
                if (self.find('#merchant').val() === '') {
                    self.find('#errors').text('Please enter a valid merchant.');
                    return;
                } else if (self.find('#created').val() === '') {
                    self.find('#errors').text('Please enter a valid date');
                    return;
                } else if (self.find('#amount').val() === '') {
                    self.find('#errors').text('Please enter an amount');
                    return;
                } else if (self.find('#currency').val() === '') {
                    self.find('#errors').text('Please select a currency');
                    return;
                }
                
                var $btn = self.find('#addtransaction').button('loading');
                
                $.ajax({
                    type: 'POST',
                    url: '/transactions/add',
                    data: {
                        authToken: authToken,
                        merchant: self.find('#merchant').val(),
                        created: self.find('#created').val(),
                        amount: self.find('#amount').val(),
                        currency: self.find('#currency').val()
                    },
                    cache: false
                })
                .done(function(data) {
                    data = JSON.parse(data);

                    $btn.button('complete');
                    setTimeout(function () {
                        $btn.button('reset');
                    }, 2000);
                    self.find('#errors').text('');
                    
                    var id = data.value.transactionID;
                    $('#transactions').loadTransactions(authToken, function(transactions) {
                        transactions
                            .find('#' + id)
                            .fadeTo('slow', 0.3)
                            .fadeTo('slow', 1.0)
                            .fadeTo('slow', 0.3)
                            .fadeTo('slow', 1.0);
                    });
                })
                .fail(function(data) {
                    data = JSON.parse(data.responseText);
                    self.find('#errors').text(data.message);
                    
                    $btn.button('error');
                    setTimeout(function () {
                        $btn.button('reset');
                    }, 2000);
                });
                
                event.preventDefault();
            });
        }
    });
    
    // Init app
    var init = function() {
        var authToken = _checkAuthToken();

        if(!authToken) {
            $.ajax({
                url: '/auth',
                cache: false
            })
            .done(function(html) {
                $('#view').html(html);
                $('#loginForm').login();
            });
        } else {
            // Load logout button
            $('#menu').html('<a href="javascript:void(0)" id="logout">Logout</a>');
            $('#logout').logout();
            
            $.ajax({
                url: '/transactions',
                cache: false
            })
            .done(function(html) {
                $('#view').html(html);
                $('#addForm').addTransaction(authToken);
                $('#transactions').loadTransactions(authToken);
            });
        }
    };
    
    init();
    
})(window.jQuery);