export const requireCoordinador = async (req, res, next) => {
  try {
    if (!req.user || req.user.role !== "coordinador") {
      return res.status(403).json({
        message: "Acceso denegado. Se requiere rol de coordinador",
      });
    }
    next();
  } catch (error) {
    return res.status(500).json({ message: error.message });
  }
};
