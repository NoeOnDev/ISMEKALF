import User from "../models/User.js";
import { Op } from "sequelize";

export class UserService {
  async register(userData) {
    const usersCount = await User.count();
    const role = usersCount === 0 ? "coordinador" : "almacen";

    return await User.create({
      ...userData,
      role,
    });
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

  async isCoordinador(userId) {
    const user = await this.findById(userId);
    return user && user.role === "coordinador";
  }
}
