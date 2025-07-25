import usePersistedState from '../react-hooks/usePersistedState';

export default function ChatBtn() {
  const [isOpen, setIsOpen] = usePersistedState('chatModalOpen', false);

  const handleOpen = () => {
    const next = !isOpen
    setIsOpen(next)
    window.dispatchEvent(new CustomEvent('chat-modal-toggle', { detail: next }))
  }
  return (
    <span onClick={handleOpen} style={{ background: 'none', border: 'none', color: 'white' }}>
      <i className="fas fa-comment"></i>
    </span>
  )
}