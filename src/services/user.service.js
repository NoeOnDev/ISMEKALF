import argon2 from "argon2";
import { UserModel } from "../models/user.model.js";

export class UserService {
  constructor(db) {
    this.userModel = new UserModel(db);
  }

  async login(username, password) {
    const user = await this.userModel.findByUsername(username);
    if (!user) {
      throw new Error("Usuario no encontrado");
    }

    const validPassword = await argon2.verify(user.password, password);
    if (!validPassword) {
      throw new Error("Contraseña incorrecta");
    }

    return {
      id: user.id,
      username: user.username,
      email: user.email,
    };
  }

  async register({ username, password, email }) {
    if (!username || !password) {
      throw new Error("Username y password son requeridos");
    }

    const hashedPassword = await argon2.hash(password);

    return await this.userModel.create({
      username,
      password: hashedPassword,
      email,
    });
  }
}
