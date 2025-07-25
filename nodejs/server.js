import express from 'express'
import cors from 'cors'
import dotenv from 'dotenv'

import mongoDB from './mongoDB/mongoDB.js'
import { initiateIo } from './socketIo.js'
import chat from './routes/chat.js'

dotenv.config()
const app = express()
app.use(cors({ origin: 'http://localhost:8000' }))
app.use(express.json())

const server = app.listen(process.env.NODE_SERVER_PORT, async () => {
  await mongoDB()
  console.log(`listening to port ${process.env.NODE_SERVER_PORT}`)
})

// Initiate SocketIo
initiateIo(server)

app.use(chat)