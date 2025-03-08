import jwt from "jsonwebtoken";
import { envConfig } from "../_config/env.config.js";
import { UserService } from "../services/userService.js";

const userService = new UserService();

export const verifyToken = async (req, res, next) => {
  try {
    const token = req.cookies.token;

    if (!token) {
      return res.status(401).json({ message: "No autorizado" });
    }

    const decoded = jwt.verify(token, envConfig.jwt.secret);
    const user = await userService.findById(decoded.id);

    if (!user) {
      return res.status(401).json({ message: "Usuario no válido" });
    }

    req.user = user;
    next();
  } catch (error) {
    return res.status(401).json({ message: "Token inválido" });
  }
};
