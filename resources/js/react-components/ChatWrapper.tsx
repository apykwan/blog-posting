import { useEffect, useState, useRef } from 'react';
import { io } from 'socket.io-client'

export default function ChatWrapper ({ username, avatar }) {
  const [isOpen, setIsOpen] = useState(false)
  const [messages, setMessages] = useState([]) 
  const [input, setInput] = useState('')
  const socketRef = useRef(null)
  const chatLogRef = useRef(null)

  const visible = isOpen ? 'chat--visible' : ''

  const handleChange = (e) => setInput(e.target.value)
  const handleSubmit = (e) => {
    e.preventDefault()
    if (socketRef.current) {

      const inputFields = {
        username,
        avatar,
        textvalue: input
      }

      socketRef.current.emit('chatMessage', inputFields)
      setInput('')
    }
  }

  const displayMessageFromServer = (data) => {
    return data.username === username ? 
      (
        <div className="chat-self">
          <div className="chat-message">
            <div className="chat-message-inner">{data.textvalue}</div>
          </div>
          <img className="chat-avatar avatar-tiny" src={data.avatar} />
        </div>
      ) : (
        <div className="chat-other">
          <a href={`/profile/${data.username}`}><img className="avatar-tiny" src={data.avatar} /></a>
          <div className="chat-message">
            <div className="chat-message-inner">
              <a href={`/profile/${data.username}`}><strong>{data.username}:</strong></a>
              {data.textvalue}
            </div>
          </div>
        </div>
      )
  }
  
  useEffect(() => {
    const handleOpen = () => setIsOpen(true)
    const handleClose = () => setIsOpen(false)
    window.addEventListener("open-chat", handleOpen)
    window.addEventListener("close-chat", handleClose)

    return () => {
      window.removeEventListener("open-chat", handleOpen)
      window.removeEventListener("close-chat", handleClose)
    }
  }, [])

  useEffect(() => {
    socketRef.current = io(`http://localhost:${import.meta.env.VITE_NODE_SERVER_PORT}`)  

    socketRef.current.on('chatMessage', (data) => {
      setMessages(prev => [...prev, data])
    })

    return () => {
      socketRef.current?.disconnect()
      socketRef.current = null
    }
  }, [])

  // Scroll to the bottom
  useEffect(() => {
    if (chatLogRef.current) {
      chatLogRef.current.scrollTop = chatLogRef.current.scrollHeight
    }
  }, [messages])
  return (
    <div className={`chat-wrapper shadow border-top border-left border-right ${visible}`}>
      <div className="chat-title-bar">
        Chat 
        <span className="chat-title-bar-close" onClick={() => setIsOpen(false)}>
          <i className="fas fa-times-circle"></i>
        </span>
      </div>
      <div className="chat-log" ref={chatLogRef}>
         {messages && messages.length > 0 && messages.map((message, idx) => (
        <div key={`${message.username}-${idx}`}>
          {displayMessageFromServer(message)}
        </div>
      ))}
      </div>
       
      <form className="chat-form border-top" onSubmit={handleSubmit}>
        <input 
          type="text" 
          className="chat-field" 
          placeholder="Type a message…" 
          autoComplete="off"
          onChange={handleChange}
          value={input} 
        />
      </form>
    </div>
  )
}