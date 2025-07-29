import Chat from '../models/chat.js'
import { emitIo } from '../utils/socketIo.js'
import { mysqlDb } from '../databases/mysql.js'
import { isRedisActive } from '../databases/redis.js'
import { cacheUsers, getCachedUsers, isCachedUsersEmpty } from '../utils/redisCache.js'

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
    let users = []
    if (isRedisActive) {
      if (await isCachedUsersEmpty()) {
        const [userRows] = await mysqlDb.query('SELECT id, username, avatar FROM users');
        if (userRows.length === 0) return res.json([]);

        await cacheUsers(userRows);
      }

      // Always get users from cache after cache is confirmed non-empty
      users = await getCachedUsers();

    } else {
      // Redis not active, fallback to DB
      const [userRows] = await mysqlDb.query('SELECT id, username, avatar FROM users');
      if (userRows.length === 0) return res.json([]);
      users = userRows;
    }

    console.log(isRedisActive, users)

    const userHash = {}
    users.forEach(user => {
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
