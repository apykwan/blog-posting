import mysql from 'mysql2'
import dotenv from 'dotenv'

dotenv.config()

const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USERNAME,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_DATABASE,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});


// Get promise-based pool
export const mysqlDb = pool.promise();

export async function testMySQLConnection() {
  try {
    // simple query to test
    const [rows] = await mysqlDb.query('SELECT 1') 
    console.log('MySQL connected in NodeJs:', rows)
  } catch (err) {
    console.error('MySQL connection failed:', err)
    throw err
  }
}
