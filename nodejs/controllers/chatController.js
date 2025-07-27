import Chat from '../models/chat.js'
import { emitIo } from '../socketIo.js'
import {  mysqlDb } from '../databases/mysql.js'

export async function createChat (req, res, next) {
  const data = req.body

  if (
    data.userId &&
    typeof data.username === 'string' && data.username.trim() !== '' &&
    typeof data.textvalue === 'string' && data.textvalue.trim() !== ''
  ) {
    try {
      await Chat.create({
        userId: Number(data.userId),
        textvalue: data.textvalue
      })

      // Broadcast to ALL clients including sender
      emitIo('chatMessage', {
        avatar: data.avatar.trim(), 
        username: data.username.trim(),
        textvalue: data.textvalue.trim()
      })
      return res.json({ status: 'Message broadcasted' })
    } catch (err) {
      console.error('Failed to save chat:', err)
      return res.status(500).json({ error: 'Failed to save chat message' })
    }
  }

  return res.status(400).json({ status: 'Invalid data' })
}

export async function getChats(req, res) {
  try {
    const [userRows] = await mysqlDb.query('SELECT id, username, avatar FROM users')
    if (userRows.length === 0) return res.json([])

    const userHash = {}
    userRows.forEach(user => {
      const avatar = user?.avatar?.startsWith('http') || user?.avatar?.startsWith('/')
        ? user.avatar
        : `/storage/avatars/${user?.avatar}`
      if (!userHash[user.id]) {
        userHash[user.id] = {
          avatar: avatar,
          username: user.username
        };
      }
    })

    const chatDocs = await Chat.find()
      .sort({ created_at: -1 })
      .limit(25)
      .select({ _id: 0, __v: 0 })
      .lean();

    const chats = chatDocs.map(c => {
      return {
        username: userHash[c.userId].username,
        avatar: userHash[c.userId].avatar,
        ...c
      }
    })
    return res.json(chats.reverse())
  } catch (err) {
    console.error('Failed to save chat:', err);
    return res.status(500).json({ error: 'Failed to save chat message' });
  }
}
