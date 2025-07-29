import { createClient } from 'redis'
import dotenv from 'dotenv'

dotenv.config()

export let isRedisActive = false

export const redisClient = createClient({
  url: `redis://${process.env.REDIS_HOST}:${process.env.REDIS_PORT}` 
})

export default async function connectRedis() {
  redisClient.on('ready', () => {
    isRedisActive = true
    console.log('Redis client connected in NodeJs')
  })
  redisClient.on('error', (err) => console.error('Redis Client Error', err))

  await redisClient.connect()
}
