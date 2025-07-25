import { useState, useEffect } from 'react'

export default function ChatBtn() {
  const [isOpen, setIsOpen] = useState(false)

  const handleOpen = () => {
    const next = !isOpen
    setIsOpen(next)
    localStorage.setItem('chatModalOpen', next.toString())
    window.dispatchEvent(new CustomEvent('chat-modal-toggle', { detail: next }))
  }

  useEffect(() => {
    const stored = localStorage.getItem('chatModalOpen') === 'true'
    setIsOpen(stored)
  }, [])
  return (
    <span onClick={handleOpen} style={{ background: 'none', border: 'none', color: 'white' }}>
      <i className="fas fa-comment"></i>
    </span>
  )
}