import mysql from "mysql2/promise";

export class UserModel {
  constructor(db) {
    this.db = db;
  }

  async createTable() {
    const query = `
      CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100)
      )
    `;
    await this.db.execute(query);
  }

  async findByUsername(username) {
    const [rows] = await this.db.execute(
      "SELECT * FROM users WHERE username = ?",
      [username]
    );
    return rows[0];
  }

  async create({ username, password, email }) {
    const [result] = await this.db.execute(
      "INSERT INTO users (username, password, email) VALUES (?, ?, ?)",
      [username, password, email]
    );
    return result.insertId;
  }
}
