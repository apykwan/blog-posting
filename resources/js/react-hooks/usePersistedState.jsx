import { useState, useEffect } from "react"

export default function usePersistedState(key, initialValue) {
  // Initialize state from localStorage (if exists) or fallback to initialValue
  const [state, setState] = useState(() => {
      try {
        const stored = localStorage.getItem(key)
        return stored !== null ? JSON.parse(stored) : initialValue
      } catch (err) {
        console.error("Failed to read localStorage:", err)
        return initialValue
      }
  })

  // Sync any state changes to localStorage
  useEffect(() => {
    try {
      localStorage.setItem(key, JSON.stringify(state))
    } catch (err) {
      console.error("Failed to write localStorage:", err)
    }
  }, [key, state])

  return [state, setState]
}
