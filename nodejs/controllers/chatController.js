import mongoose from 'mongoose'

import Chat from '../models/chat.js'
import User from '../models/user.js'
import { emitIo } from '../socketIo.js'
import {  mysqlDb } from '../database/mysql.js'

export async function createChat (req, res, next) {
    const data = req.body
  if (
    typeof data.username === 'string' && data.username.trim() !== '' &&
    typeof data.textvalue === 'string' && data.textvalue.trim() !== ''
  ) {
    try {
      const user = await User.findOne({ username: data.username }).exec()

      if (!user) next(new Error('User not found.'))

      await Chat.create({
        user: new mongoose.Types.ObjectId(user._id),
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

export async function getChats(req, res) {
  try {
    const chats = await Chat.find()
      .sort({ created_at: -1 })
      .limit(25)
      .select({ _id: 0, __v: 0 })
      .populate('user', 'username avatar -_id')
      .lean();


    const [userRows] = await mysqlDb.query('SELECT id, username, avatar FROM users')
    
    console.log('all users: ', userRows)
    const flattenedChats = chats.map(chat => {
      const { user, ...rest } = chat;
      const avatar =
        user?.avatar?.startsWith('http') || user?.avatar?.startsWith('/')
          ? user.avatar
          : `/storage/avatars/${user?.avatar}`;

      return {
        ...rest,
        username: user?.username,
        avatar
      };
    });

    console.log('data', flattenedChats);

    return res.json(flattenedChats.reverse());
  } catch (err) {
    console.error('Failed to save chat:', err);
    return res.status(500).json({ error: 'Failed to save chat message' });
  }
}
