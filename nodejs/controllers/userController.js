import User from '../models/user.js'

export async function createUser(req, res, next) {
  try {
    const { sql_id, username, avatar } = req.body;

    if (!sql_id || !username || !avatar) {
      return next(new Error ('sql_id, username and avatar are required.'));
    }

    await User.create({ sql_id, username, avatar });

    return res.status(200).json({ msg: 'user has been created' })
  } catch (err) {
    console.log(err.message);
  }
}

export async function updateUser(req, res, next) {
  try {
    const { avatar } = req.body;

    if (!avatar) {
      return next(new Error ('Avatar is required.'));
    }

    const updatedUser = await User.findOneAndUpdate(
      { username: req.params.username },
      { avatar },
      { new: true }
    )

    if (!updatedUser) {
      return res.status(404).json({ error: 'User not found' })
    }

    return res.status(200).json({ msg: 'user avatar has been updated' })
  } catch (err) {
    console.log(err.message);
  }
}
