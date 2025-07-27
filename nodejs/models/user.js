import { Schema, model } from 'mongoose'

const userSchema = new Schema({
  username: {
    type: String,
    required: true
  },
  avatar: {
    type: String,
    required: true
  },
  sql_id: {
    type: Number,
    required: true
  }
})

export default model('User', userSchema)