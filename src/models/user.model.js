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
        email VARCHAR(100) UNIQUE,
        role ENUM('coordinador', 'almacen') NOT NULL
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

  async findByEmailOrUsername(emailOrUsername) {
    const [rows] = await this.db.execute(
      "SELECT * FROM users WHERE email = ? OR username = ?",
      [emailOrUsername, emailOrUsername]
    );
    return rows[0];
  }

  async create({ username, password, email, role }) {
    const [result] = await this.db.execute(
      "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)",
      [username, password, email, role]
    );
    return result.insertId;
  }

  async getFirstUser() {
    const [rows] = await this.db.execute(
      "SELECT * FROM users ORDER BY id LIMIT 1"
    );
    return rows[0];
  }

  async getAllWarehouseUsers() {
    const [rows] = await this.db.execute(
      "SELECT id, username, email FROM users WHERE role = 'almacen'"
    );
    return rows;
  }

  async updateWarehouseUser(id, { username, password, email }) {
    const updates = [];
    const values = [];

    if (username) {
      updates.push("username = ?");
      values.push(username);
    }
    if (password) {
      updates.push("password = ?");
      values.push(password);
    }
    if (email) {
      updates.push("email = ?");
      values.push(email);
    }

    if (updates.length === 0) return null;

    values.push(id);
    const query = `UPDATE users SET ${updates.join(
      ", "
    )} WHERE id = ? AND role = 'almacen'`;
    const [result] = await this.db.execute(query, values);
    return result.affectedRows > 0;
  }

  async deleteWarehouseUser(id) {
    const [result] = await this.db.execute(
      "DELETE FROM users WHERE id = ? AND role = 'almacen'",
      [id]
    );
    return result.affectedRows > 0;
  }
}
