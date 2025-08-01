import express from 'express'

import { createChat, getChats } from '../controllers/chatController.js'
import { isAuthenticated } from '../middlewares/auth.js'

const router = express.Router()

router.route('/send-chat-message').post(isAuthenticated, createChat)
router.route('/get-chat-messages').get(isAuthenticated, getChats)

export default router