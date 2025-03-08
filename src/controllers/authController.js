import jwt from "jsonwebtoken";
import { UserService } from "../services/userService.js";
import { envConfig } from "../_config/env.config.js";

const userService = new UserService();

export class AuthController {
  async register(req, res) {
    try {
      const { username, email, password } = req.body;

      const user = await userService.register({
        username,
        email,
        password,
      });

      // Excluimos la contraseña de la respuesta
      const { password: _, ...userWithoutPassword } = user.toJSON();

      res.status(201).json({
        message: "Usuario registrado exitosamente",
        user: userWithoutPassword,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al registrar usuario",
        error: error.message,
      });
    }
  }

  async login(req, res) {
    try {
      const { email, password } = req.body;

      const user = await userService.login(email, password);

      const token = jwt.sign({ id: user.id }, envConfig.jwt.secret, {
        expiresIn: envConfig.jwt.accessExpiration,
      });

      const { password: _, ...userWithoutPassword } = user.toJSON();

      res.json({
        message: "Inicio de sesión exitoso",
        user: userWithoutPassword,
        token,
      });
    } catch (error) {
      res.status(401).json({
        message: "Error al iniciar sesión",
        error: error.message,
      });
    }
  }
}
