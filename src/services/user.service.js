import argon2 from "argon2";
import { UserModel } from "../models/user.model.js";
import { TokenManager } from "../utils/jwt.utils.js";

export class UserService {
  constructor(db) {
    this.userModel = new UserModel(db);
  }

  async login(emailOrUsername, password) {
    if (!emailOrUsername || !password) {
      throw new Error("Email/Username y password son requeridos");
    }

    const user = await this.userModel.findByEmailOrUsername(emailOrUsername);
    if (!user) {
      throw new Error("Usuario no encontrado");
    }

    const validPassword = await argon2.verify(user.password, password);
    if (!validPassword) {
      throw new Error("Contraseña incorrecta");
    }

    const tokenPayload = {
      userId: user.id,
      role: user.role,
    };

    const accessToken = TokenManager.generateAccessToken(tokenPayload);
    const refreshToken = TokenManager.generateRefreshToken(tokenPayload);

    return {
      accessToken,
      refreshToken,
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        role: user.role,
      },
    };
  }

  async register({ username, password, email }) {
    if (!username || !password) {
      throw new Error("Username y password son requeridos");
    }

    if (email) {
      const existingUser = await this.userModel.findByEmailOrUsername(email);
      if (existingUser) {
        throw new Error("El email ya está registrado");
      }
    }

    const existingUser = await this.userModel.findByEmailOrUsername(username);
    if (existingUser) {
      throw new Error("El username ya está registrado");
    }

    const firstUser = await this.userModel.getFirstUser();
    const role = firstUser ? "almacen" : "coordinador";

    const hashedPassword = await argon2.hash(password);

    return await this.userModel.create({
      username,
      password: hashedPassword,
      email,
      role,
    });
  }

  async getWarehouseUsers() {
    return await this.userModel.getAllWarehouseUsers();
  }

  async updateWarehouseUser(id, data) {
    if (data.password) {
      data.password = await argon2.hash(data.password);
    }

    const updated = await this.userModel.updateWarehouseUser(id, data);
    if (!updated) {
      throw new Error("Usuario no encontrado o no es un usuario de almacén");
    }
    return updated;
  }

  async deleteWarehouseUser(id) {
    const deleted = await this.userModel.deleteWarehouseUser(id);
    if (!deleted) {
      throw new Error("Usuario no encontrado o no es un usuario de almacén");
    }
    return deleted;
  }

  async refreshToken(refreshToken) {
    try {
      const decoded = TokenManager.verifyToken(refreshToken);
      const user = await this.userModel.findById(decoded.userId);

      if (!user) throw new Error("Usuario no encontrado");

      const tokenPayload = {
        userId: user.id,
        role: user.role,
      };

      return {
        accessToken: TokenManager.generateAccessToken(tokenPayload),
      };
    } catch (error) {
      throw new Error("Refresh token inválido");
    }
  }
}
