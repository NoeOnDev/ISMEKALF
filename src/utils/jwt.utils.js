import jwt from "jsonwebtoken";
import { envConfig } from "../config/env.config.js";

export class TokenManager {
  static generateAccessToken(payload) {
    return jwt.sign(payload, envConfig.jwt.secret, {
      expiresIn: envConfig.jwt.accessExpiration,
    });
  }

  static generateRefreshToken(payload) {
    return jwt.sign(payload, envConfig.jwt.secret, {
      expiresIn: envConfig.jwt.refreshExpiration,
    });
  }

  static verifyToken(token) {
    try {
      return jwt.verify(token, envConfig.jwt.secret);
    } catch (error) {
      throw new Error("Token inválido");
    }
  }
}
