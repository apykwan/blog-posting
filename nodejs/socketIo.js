import { Server } from 'socket.io'

let io = null
export function initiateIo(server) {
  io = new Server(server, {
    cors: {
      origin: 'http://localhost:8000',
      methods: ['GET', 'POST'],
      allowedHeaders: ['content-type']
    }
  })
  // Define Socket.IO event listeners ONCE globally
  io.on('connection', (socket) => {
    console.log("User connected:", socket.id)
  })
}

export function emitIo(event, data) {
  io.emit(event, data)
}