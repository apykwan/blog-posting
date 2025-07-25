import Chat from '../models/chat.js'
import { emitIo } from '../socketIo.js'

export async function createChat (req, res) {
    const data = req.body
  if (
    typeof data.username === 'string' && data.username.trim() !== '' &&
    typeof data.avatar === 'string' && data.avatar.trim() !== '' &&
    typeof data.textvalue === 'string' && data.textvalue.trim() !== ''
  ) {
    try {
      await Chat.create({
        username: data.username,
        avatar: data.avatar,
        textvalue: data.textvalue
      })

      // Broadcast to ALL clients including sender
      emitIo('chatMessage', data)
      return res.json({ status: 'Message broadcasted' })
    } catch (err) {
      console.error('Failed to save chat:', err)
      return res.status(500).json({ error: 'Failed to save chat message' })
    }
  }

  return res.status(400).json({ status: 'Invalid data' })
}

export async function getChats (req, res) {
  try {
    const chat = await Chat.find()
      .sort({ created_at: -1 })
      .limit(25)
      .select({ _id: 0, __v: 0 })
      .lean();

    return res.json(chat.reverse())
  } catch (err) {
    console.error('Failed to save chat:', err)
    return res.status(500).json({ error: 'Failed to save chat message' })
  }
}