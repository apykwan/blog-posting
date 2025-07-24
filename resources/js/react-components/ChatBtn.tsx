import { useState, useEffect } from 'react'

export default function ChatBtn() {
  const [isOpen, setIsOpen] = useState(false)

  const handleOpen = () => setIsOpen(prevState => !prevState)

  useEffect(() => {
    const eventName = isOpen ? 'open-chat' : 'close-chat'
    window.dispatchEvent(new CustomEvent(eventName))
  }, [isOpen])
  return (
    <span onClick={handleOpen} style={{ background: 'none', border: 'none', color: 'white' }}>
      <i className="fas fa-comment"></i>
    </span>
  )
}