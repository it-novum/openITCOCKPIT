angular.module('openITCOCKPIT')
    .service('SudoService', function($interval){

        var _connection = null;
        var _uniqid = null;
        var _url = '';
        var _key = '';

        var _onSuccess = function(event){
            console.info(event);
            _reconnectAttempt = 1;

            $('#globalSudoServerCouldNotConnect').hide();
            $('#globalSudoServerLostConnection').hide();
        };

        var _keepAliveInterval = null;
        var _reconnectAttempt = 1;
        var _isReconnectScheduled = false;

        var _onError = function(event){
            console.error(event);
            $('#globalSudoServerCouldNotConnect').show();

            // Disable keepAlive
            if(_keepAliveInterval){
                $interval.cancel(_keepAliveInterval);
                _keepAliveInterval = null;
            }

            if(_isReconnectScheduled === false){
                console.log('Schedule SudoServer reconnect in ' + (_reconnectAttempt * 10) + ' seconds');
                //No reconnect is running - schedule reconnect
                setTimeout(function(){
                    console.log('Reconnect onError');
                    _connect();
                }, (_reconnectAttempt * 10 * 1000));
                _isReconnectScheduled = true;
                _reconnectAttempt++;
            }

        };

        var _onClose = function(event){
            console.error(event);

            // Disable keepAlive
            if(_keepAliveInterval){
                $interval.cancel(_keepAliveInterval);
                _keepAliveInterval = null;
            }

            if(_isReconnectScheduled === false){

                $('#globalSudoServerLostConnection').show();

                console.log('Schedule SudoServer reconnect in ' + (_reconnectAttempt * 10) + ' seconds');
                //No reconnect is running - schedule reconnect
                setTimeout(function(){
                    console.log('Reconnect _onClose');
                    _connect();
                }, (_reconnectAttempt * 10 * 1000));
                _isReconnectScheduled = true;
                _reconnectAttempt++;
            }
        };

        var _onResponse = function(event){
            //console.log(event);
        };

        var _onDispatch = function(event){
            //console.log(event);
        };

        var keepAlive = function(){
            _send(JSON.stringify({
                task: 'keepAlive',
                data: '',
                uniqid: _uniqid,
                key: _key
            }));
        };

        var _connect = function(){
            _connection = new WebSocket(_url);

            _isReconnectScheduled = false;


            _connection.onopen = _onResponse;
            _connection.onmessage = _parseResponse;
            _connection.onerror = _onError;
            _connection.onclose = _onClose;

            _keepAliveInterval = $interval(function(){
                keepAlive();
            }, 30000);
        };

        var _send = function(json){
            _connection.send(json);
        };

        var _parseResponse = function(event){
            var transmitted = JSON.parse(event.data);
            switch(transmitted.type){
                case 'connection':
                    //New connection was established successfully
                    //Save UUID, the server give us
                    _uniqid = transmitted.uniqid;


                    //Trigger _onSuccess callback and start keepAlive
                    _onSuccess(event);
                    break;

                case 'response':
                    //Server response to a request we sent
                    if(_uniqid === transmitted.uniqid){
                        _onResponse(event);
                    }
                    break;

                case 'dispatcher':
                    //Received some broadcast message
                    _onDispatch(event);
                    break;

                case 'keepAlive':
                    // Server is still alive :-)
                    break;
            }
        };

        return {
            toJson: function(task, data){
                var jsonArr = [];
                jsonArr = JSON.stringify({
                    task: task,
                    data: data,
                    uniqid: _uniqid,
                    key: _key
                });
                return jsonArr;
            },
            setApiKey: function(key){
                _key = key;
            },
            setUrl: function(url){
                _url = url;
            },
            connect: function(){
                _connect();
            },
            onSuccess: function(callback){
                _onSuccess = callback;
            },
            onError: function(callback){
                _onError = callback;
            },
            onClose: function(callback){
                _onClose = callback;
            },
            onResponse: function(callback){
                _onResponse = callback;
            },
            onDispatch: function(callback){
                _onDispatch = callback;
            },
            send: function(json){
                _send(json);
            }
        }
    });
