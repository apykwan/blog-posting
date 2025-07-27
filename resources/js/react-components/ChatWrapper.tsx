import { useEffect, useState, useRef } from 'react'
import axios from 'axios'
import { io } from 'socket.io-client'

import usePersistedState from '../react-hooks/usePersistedState';

export default function ChatWrapper ({ username }) {
  const [isOpen, setIsOpen] = usePersistedState('chatModalOpen', false);
  const [messages, setMessages] = useState([]) 
  const [input, setInput] = useState('')
  const socketRef = useRef(null)
  const chatLogRef = useRef(null)

  const visible = isOpen ? 'chat--visible' : ''

  const handleChange = (e) => setInput(e.target.value)
  const handleSubmit = async (e) => {
    e.preventDefault()

    if (socketRef.current) {
      await axios.post('http://localhost:8000/send-chat-message', {
        textvalue: input
      },  { withCredentials: true })

      setInput('')
    }
  }

  const handleCloseBtn = () => setIsOpen(false)

  const displayMessageFromServer = (data) => {
    if (!data.username || !data.avatar) return

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
          <a href={`/profile/${data.username}`}>
            <img className="avatar-tiny" src={data.avatar} />
          </a>
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
    async function fetchMessages() {
      const { data } = await axios(`http://localhost:${import.meta.env.VITE_NODE_SERVER_PORT}/get-chat-messages`)
       setMessages(data);
    }

    if (messages.length === 0) fetchMessages()
  }, [])
  
  useEffect(() => {
    const onToggle = (e) => setIsOpen(e.detail)
    const onStorage = (e) => {
      if (e.key === 'chatModalOpen') setIsOpen(e.newValue === 'true')
    }

    window.addEventListener('chat-modal-toggle', onToggle)
    window.addEventListener('storage', onStorage)

    // Initialize from localStorage on mount
    setIsOpen(localStorage.getItem('chatModalOpen') === 'true')

    return () => {
      window.removeEventListener('chat-modal-toggle', onToggle)
      window.removeEventListener('storage', onStorage)
    }
  }, [])

  useEffect(() => {
    socketRef.current = io(`http://localhost:${import.meta.env.VITE_NODE_SERVER_PORT}`)  

    socketRef.current.on('chatMessage', (data) => {
      console.log('emit', data)
      if (!data.username || !data.avatar) return
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
        <span className="chat-title-bar-close" onClick={handleCloseBtn}>
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