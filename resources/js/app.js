// require('./bootstrap');

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js')
window.Echo = new Echo({
    broadcaster: 'pusher', 
    key: '881450d98cf7334c4117', 
    cluster: 'eu', 
    encrypted: true
})