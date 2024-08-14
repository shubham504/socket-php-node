$(function () {
    var socket = io();

    // Load existing messages
    $.get('http://localhost/chatgpt-chatapp/with-sql/db.php', function (data) {
        const messages = JSON.parse(data);
        messages.reverse().forEach(function (msg) {
            $('#messages').append($('<li>').text(msg.user + ': ' + msg.message));
        });
    });

    // Handle form submission
    $('#chat-form').submit(function (e) {
        e.preventDefault();
        const user = $('#user').val();
        const message = $('#message').val();

        if (user && message) {
            // Emit message to socket
            socket.emit('chat message', user + ': ' + message);

            // Save message to database
            $.post('http://localhost/chatgpt-chatapp/with-sql/db.php', { user: user, message: message });

            $('#message').val('');
        }
        return false;
    });

    // Listen for incoming messages
    socket.on('chat message', function (msg) {
        $('#messages').append($('<li>').text(msg));
    });
});
