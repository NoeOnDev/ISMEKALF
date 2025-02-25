import { TokenManager } from "../utils/jwt.utils.js";

export const authenticateToken = async (req, res, next) => {
  try {
    const authHeader = req.headers.authorization;
    const token = authHeader && authHeader.split(" ")[1];

    if (!token) {
      return res.status(401).json({
        success: false,
        message: "Token no proporcionado",
      });
    }

    const decoded = TokenManager.verifyToken(token);
    req.user = decoded;
    next();
  } catch (error) {
    return res.status(401).json({
      success: false,
      message: "Token inválido",
    });
  }
};

export const isCoordinator = (req, res, next) => {
  if (req.user.role !== "coordinador") {
    return res.status(403).json({
      success: false,
      message: "Acceso denegado: Se requiere rol de coordinador",
    });
  }
  next();
};
