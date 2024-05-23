const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const cors = require("cors");

const app = express();
const server = http.createServer(app);

app.use(
    cors({
        origin: "*",
    })
);

const io = socketIO(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

app.use(express.json());
io.on('connection', (socket) => {
    console.log('A user connected with ID:', socket.id);
    

    socket.on('disconnect', () => {
        console.log('User disconnected with ID:', socket.id);
    });

app.post('/product-channel', (req, res) => {
    const product = req.body.productData;

      console.log("the new product :", product);
      socket.broadcast.emit('new-product', product);

    res.status(200).json({ message: 'Product data received' });
});
});
const PORT = process.env.PORT || 6001;
server.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
