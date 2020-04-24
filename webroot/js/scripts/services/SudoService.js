angular.module('openITCOCKPIT')
    .service('SudoService', function($interval){

        var _connection = null;
        var _uniqid = null;
        var _url = '';
        var _key = '';

        var _hasError = false;

        var _onSuccess = function(event){
            console.info(event)
        };

        var _onError = function(event){
            _hasError = true;
            $('#globalSudoServerCouldNotConnect').show();
            console.error(event);
        };

        var _onClose = function(event){
            console.error(event);
            if(_hasError === false){
                $('#globalSudoServerLostConnection').show();
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


            _connection.onopen = _onResponse;
            _connection.onmessage = _parseResponse;
            _connection.onerror = _onError;
            _connection.onclose = _onClose;

            var keepAliveInterval = $interval(function(){
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
            },
            hasError: function(){
                return _hasError;
            }
        }
    });
