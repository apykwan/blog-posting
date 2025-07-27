import express from 'express'
import cors from 'cors'
import dotenv from 'dotenv'

import mongoDB from './database/mongoDB.js'
import { testMySQLConnection } from './database/mysql.js'
import { initiateIo } from './socketIo.js'
import chat from './routes/chat.js'
import user from './routes/user.js'

dotenv.config()
const app = express()
app.use(cors({ origin: '*' }))
app.use(express.json())

const server = app.listen(process.env.NODE_SERVER_PORT, async () => {
  try {
    await mongoDB();
    await testMySQLConnection();
    console.log(`Listening on port ${process.env.NODE_SERVER_PORT}`);
  } catch (error) {
    console.error('Failed to start server:', error);
    process.exit(1); 
  }
});

// Initiate SocketIo
initiateIo(server)

app.use(chat)
app.use(user)
app.use((err, req, res, next) => {
  const statusCode = err.status || 500;
  return res.status(statusCode).json({ msg: err.message || 'Server error '});
});