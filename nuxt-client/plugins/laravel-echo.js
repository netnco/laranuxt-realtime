import Echo from 'laravel-echo'

//window.io = require('socket.io-client')
window.Pusher = require('pusher-js')
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '373c14126a5cf6ef7c8d',
    //host: 'http://laranuxt-realtime:6001',
    wsHost: 'laranuxt-realtime',
    wsPort: 6001,
    disableStats: true,
    enabledTransports: ['ws'],
})
