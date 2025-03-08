import User from "../models/User.js";
import { Op } from "sequelize";

export class UserService {
  async register(userData) {
    return await User.create(userData);
  }

  async login(emailOrUsername, password) {
    const user = await User.findOne({
      where: {
        [Op.or]: [{ email: emailOrUsername }, { username: emailOrUsername }],
      },
    });

    if (!user) {
      throw new Error("Usuario no encontrado");
    }

    const isValidPassword = await user.comparePassword(password);
    if (!isValidPassword) {
      throw new Error("Contraseña incorrecta");
    }

    return user;
  }

  async findById(id) {
    return await User.findByPk(id, {
      attributes: { exclude: ["password"] },
    });
  }
}
