import jwt from "jsonwebtoken";
import { UserService } from "../services/userService.js";
import { envConfig } from "../_config/env.config.js";

const userService = new UserService();

export class AuthController {
  async registerFirst(req, res) {
    try {
      const { username, email, password } = req.body;

      const user = await userService.registerFirst({
        username,
        email,
        password,
      });

      const { password: _, ...userWithoutPassword } = user.toJSON();

      res.status(201).json({
        message: "Coordinador registrado exitosamente",
        user: userWithoutPassword,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al registrar coordinador",
        error: error.message,
      });
    }
  }

  async register(req, res) {
    try {
      const { username, email, password } = req.body;

      const user = await userService.register({
        username,
        email,
        password,
      });

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
      const { emailOrUsername, password } = req.body;

      if (!emailOrUsername || !password) {
        return res.status(400).json({
          message: "Por favor proporcione un email/username y contraseña",
        });
      }

      const user = await userService.login(emailOrUsername, password);

      const token = jwt.sign({ id: user.id }, envConfig.jwt.secret, {
        expiresIn: envConfig.jwt.accessExpiration,
      });

      const { password: _, ...userWithoutPassword } = user.toJSON();

      res.cookie("token", token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === "production",
        sameSite: "strict",
        maxAge: 3600000,
        path: "/",
      });

      res.json({
        message: "Inicio de sesión exitoso",
        user: userWithoutPassword,
      });
    } catch (error) {
      res.status(401).json({
        message: "Error al iniciar sesión",
        error: error.message,
      });
    }
  }

  async logout(req, res) {
    res.cookie("token", "", {
      httpOnly: true,
      expires: new Date(0),
    });

    res.json({
      message: "Sesión cerrada exitosamente",
    });
  }
}
