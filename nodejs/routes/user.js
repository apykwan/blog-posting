import express from 'express'

import { createUser, updateUser } from '../controller/userController.js'

const router = express.Router()

router.route('/create-mongodb-user').post(createUser)
router.route('/update-mongodb-user/:username').put(updateUser)

export default router