import express from 'express'

import { createChat, getChats } from '../controller/chatController.js'

const router = express.Router()

router.route('/send-chat-message').post(createChat)
router.route('/get-chat-messages').get(getChats)

export default router