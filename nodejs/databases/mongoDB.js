import mongoose from 'mongoose'

export default async function () {
  if (mongoose.connection.readyState >= 1) return

  try {
      await mongoose.connect(process.env.MONGODB_URL)
      console.log("Connected to mongoDB in NodeJs")
  } catch (err) {
      console.log(`DB connection Error: `, err)
  }
}