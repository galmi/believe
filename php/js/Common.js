var Common = {

    searchInt:0, //Какая то переменная, обратится можно как Common.abc
    profileuser:{},
    like: null,

    addStory: function(elText, elButton) {
        var text = $.trim($(elText).val());
        var believe = $('.mystory.checkbox.on').attr('data-value');
        var button = $(elButton);

        if (text.length==0) {
            alert('Напиши историю');
            return;
        }
        if (believe!='believe' && believe!='not_believe') {
            alert('Отметь Правда или Ложь');
            return;
        }

        button.hide();
        button.parent().find('img').show();
        $.post('/story/add', {story:text, value:believe}, function(response){
            if (response.success==true) {
                document.location.reload();
            } else {
                alert(response.error);
            }
            button.parent().find('img').hide();
            button.show();
        },'json');
    },

    getAppFriends:function (uids) {
        VK.api('friends.getAppUsers', function (data) {
            var friendsApp = {};
            friendsApp['friendsApp'] = data['response'];
            $.ajax({url:'/user/appusers', type:'POST', data:$.param(friendsApp)});
        });
    },

    viewUser: function(callback){
        VK.api("getProfiles", {uids:Common.profileuser["uid"],fields:"uid,photo_rec"}, function (profiles) {
            $.each(profiles, function(responce, objFriend){
                $.each(objFriend, function(num, friend){
                    $.each(friend, function(param, val){
                        Common.profileuser[param] = val;
                    });
                });
            });
            callback();
        });
    },

    url : function(url) {
        document.location.href = url;
    },

    vote: function(story_id, el) {
        var value = $(el).attr('data-value');
        var prnt = $(el).parent().parent();
        prnt.html('');
        $.post('/story/vote', {id: story_id, value:value},function(response){
            if (response.success==true) {
                var data = response.data;
                var result = '<div class="fl_l news_header value">' +
                    '<span class="value">'+Feed.values[data.value]+'</span></div>'+
                    '<p><span class="news_header">Верят: '+data.believe_count+'</span><br/>'+'<span class="news_header">Не верят: '+data.not_believe_count+'</span>';
                prnt.html(result);
            }
        },'json');
    },

    //Обработчик события клика "Мне нравится"
    initObserver: function() {
        if (!VK.Observer) {
            VK.Observer = {
                _subscribers: function() {
                    if (!this._subscribersMap) {
                        this._subscribersMap = {};
                    }
                    return this._subscribersMap;
                },
                publish: function(eventName) {
                    var
                        args = Array.prototype.slice.call(arguments),
                        eventName = args.shift(),
                        subscribers = this._subscribers()[eventName],
                        i, j;

                    if (!subscribers) return;

                    for (i = 0, j = subscribers.length; i < j; i++) {
                        if(subscribers[i] != null) {
                            subscribers[i].apply(this, args);
                        }
                    }
                },
                subscribe: function(eventName, handler) {
                    var
                        subscribers = this._subscribers();

                    if(typeof handler != 'function') return false;

                    if(!subscribers[eventName]) {
                        subscribers[eventName] = [handler];
                    } else {
                        subscribers[eventName].push(handler);
                    }
                },
                unsubscribe: function(eventName, handler) {
                    var
                        subscribers = this._subscribers()[eventName],
                        i, j;

                    if (!subscribers) return false;
                    if (typeof handler == 'function') {
                        for (i = 0, j = subscribers.length; i < j; i++) {
                            if (subscribers[i] == handler) {
                                subscribers[i] = null;
                            }
                        }
                    } else {
                        delete this._subscribers()[eventName];
                    }
                }
            }
        }

        VK.Observer.subscribe('widgets.like.unliked',function(){
            if (Common.like) {
                $.post('/intention/wantsview/'+Common.like, {wants:0});
            }
        });
        VK.Observer.subscribe('widgets.like.liked',function(){
            if (Common.like) {
                $.post('/intention/wantsview/'+Common.like, {wants:1});
            }
        });
        $('.like').live('mouseover', function(){
            Common.like = $(this).attr('data-id');
        });
    }

}