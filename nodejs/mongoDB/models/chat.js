import { Schema, model } from 'mongoose'

const chatSchema = new Schema({
  username: {
    type: String,
    required: true
  },
  avatar: {
    type: String,
    required: true
  },
  textvalue: {
    type: String,
    required: true
  },
  created_at: {
    type: Date,
    default: Date.now
  }
})

export default model('Chat', chatSchema)