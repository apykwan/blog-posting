import express from 'express'
import cors from 'cors'
import dotenv from 'dotenv'
import { Server } from 'socket.io'

import mongoDB from './mongoDB/mongoDB.js'
import Chat from './mongoDB/models/chat.js'

dotenv.config()
const app = express()
app.use(cors())
app.use(express.json())

const server = app.listen(process.env.NODE_SERVER_PORT, async () => {
  await mongoDB()
  console.log(`listening to port ${process.env.NODE_SERVER_PORT}`)
})

const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
    allowedHeaders: ['content-type']
  }
})

// Define Socket.IO event listeners ONCE globally
io.on('connection', (socket) => {
  console.log("User connected:", socket.id)

  // // When client sends a message via socket
  // socket.on("chatMessage", (data) => {
  //   // Broadcast to ALL clients including sender
  //   io.emit("chatMessage", data)
  // })
})

// Client sends chat message to this HTTP endpoint after Laravel validate the input and user authentication
app.post('/send-chat-message', async (req, res) => {
    console.log('POST /send-chat-message hit', req.body);
    const data = req.body;
    console.log('Received data:', data)

  if (
    typeof data.username === 'string' && data.username.trim() !== '' &&
    typeof data.avatar === 'string' && data.avatar.trim() !== '' &&
    typeof data.textvalue === 'string' && data.textvalue.trim() !== ''
  ) {
    try {
      await Chat.create({
        username: data.username,
        avatar: data.avatar,
        textvalue: data.textvalue
      })

      // Broadcast to ALL clients including sender
      io.emit('chatMessage', data)
      return res.json({ status: 'Message broadcasted' })
    } catch (err) {
      console.error('Failed to save chat:', err)
      return res.status(500).json({ error: 'Failed to save chat message' })
    }
  }

  return res.status(400).json({ status: 'Invalid data' })
})