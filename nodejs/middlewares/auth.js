import jwt from 'jsonwebtoken'

import { findUserById } from '../utils/helpers.js'

export async function isAuthenticated(req, res, next) {
  const authHeader = req.headers['authorization']
  const token = authHeader && authHeader.split(' ')[1]

  if (!token) next(new Error('Missing Token'))

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET)

     const userExists = await findUserById(decoded.id)
    if (!userExists) next(new Error('User not found'))
    
    req.user = {
      userId: decoded.id,
      avatar: userExists.avatar,
      username: userExists.username,
      isAdmin: decoded.isAdmin === 1
    }

    next()
  } catch (err) {
    if (err.name === 'TokenExpiredError') {
        return next(new Error('Token has been exired'))
    }
    return next(new Error('Invalid token'))
  }
}