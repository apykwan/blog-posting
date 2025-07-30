import { redisClient } from '../databases/redis.js'
import { mysqlDb } from '../databases/mysql.js'

// Redis helpers
export async function cacheUsers(users) {
  if (users.length === 0) return
  for (const user of users) {
    const avatar = user.avatar.startsWith('http') || user.avatar.startsWith('/')
      ? user.avatar
      : `/storage/avatars/${user.avatar}`

    await redisClient.hSet(`user:${user.id}`, {
      username: user.username,
      avatar: avatar
    })

    await redisClient.sAdd('users', user.id.toString())
  }
}

export async function getCachedUsers() {
  const userIds = await redisClient.sMembers('users');
  const users = [];

  for (const id of userIds) {
    const user = await redisClient.hGetAll(`user:${id}`)
    if (Object.keys(user).length) { 
      users.push({ id, ...user })
    }
  }

  return users;
}

export async function isCachedUsersEmpty() {
  const members = await redisClient.sMembers('users')
  return members.length === 0
}

// MySQL helper
export async function fetchUsersFromMySQL() {
  const [userRows] = await mysqlDb.query('SELECT id, username, avatar FROM users')

  return userRows
}