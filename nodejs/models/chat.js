import { Schema, model } from 'mongoose'

const chatSchema = new Schema({
  userId: {
    type: Number,
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