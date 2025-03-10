import { Router } from "express";
import { AuthController } from "../controllers/authController.js";
import { verifyToken } from "../middleware/authMiddleware.js";
import { requireCoordinador } from "../middleware/roleMiddleware.js";

const router = Router();
const authController = new AuthController();

router.post("/register/first", authController.registerFirst);
router.post(
  "/register",
  verifyToken,
  requireCoordinador,
  authController.register
);
router.post("/login", authController.login);
router.post("/logout", authController.logout);

export default router;
