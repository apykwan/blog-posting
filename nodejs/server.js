import http from 'http' 
import express from 'express'
import cors from 'cors'
import dotenv from 'dotenv'

import mongoDB from './databases/mongoDB.js'
import { testMySQLConnection } from './databases/mysql.js'
import connectRedis from './databases/redis.js'
import { initiateIo } from './utils/socketIo.js'
import chat from './routes/chat.js'

dotenv.config()
const app = express()
const server = http.createServer(app) 
app.use(cors({ origin: '*' }))
app.use(express.json())

// Initiate SocketIo
initiateIo(server)

server.listen(process.env.NODE_SERVER_PORT, async () => {
  try {
    await mongoDB()   
    await testMySQLConnection()   

    try {
      await connectRedis()
      console.log('Connected to Redis')
    } catch (redisError) {
      console.warn('Warning: Failed to connect to Redis:', redisError)
    }

    console.log(`Listening on port ${process.env.NODE_SERVER_PORT}`)
  } catch (error) {
    console.error('Failed to start server:', error)
    process.exit(1)
  }
})

app.use('/api', chat)
app.use((err, req, res, next) => {
  const statusCode = err.status || 500;
  return res.status(statusCode).json({ msg: err.message || 'Server error '});
})