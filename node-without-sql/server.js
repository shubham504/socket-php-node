const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

// Set up the Express app and server
const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Serve static files from the "public" directory
app.use(express.static('public'));

// Handle a connection
io.on('connection', (socket) => {
    console.log('A user connected');

    // Broadcast a message to all connected clients when a new message is received
    socket.on('chat message', (msg) => {
        io.emit('chat message', msg);
    });

    // Handle disconnect
    socket.on('disconnect', () => {
        console.log('A user disconnected');
    });
});

// Start the server
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
