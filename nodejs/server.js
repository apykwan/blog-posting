import express from 'express';
import http from 'http';
import cors from 'cors';
import dotenv from 'dotenv';
import { Server } from 'socket.io';

dotenv.config();

const app = express();
app.use(cors());
app.use(express.json());

const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
    allowedHeaders: ['content-type']
  }
});

// Define Socket.IO event listeners ONCE globally
io.on('connection', (socket) => {
  console.log("User connected:", socket.id)

  // When client sends a message via socket
  socket.on("chatMessage", (data) => {
    // Broadcast to ALL clients including sender
    io.emit("chatMessage", data)
  })
})

// Laravel sends chat message to this HTTP endpoint
app.post('/send-chat-message', (req, res) => {
  const data = req.body;
  console.log('Received from Laravel:', data);

  // Broadcast to all connected clients
  io.emit('chatMessage', data);

  res.json({ status: 'Message broadcasted' });
});

server.listen(process.env.NODE_SERVER_PORT, () => {
  console.log(`listening to port ${process.env.NODE_SERVER_PORT}`);
});
