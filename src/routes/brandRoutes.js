import { Router } from "express";
import { BrandController } from "../controllers/brandController.js";
import { verifyToken } from "../middleware/authMiddleware.js";

const router = Router();
const brandController = new BrandController();

router.get("/", verifyToken, brandController.findAll);
router.post("/", verifyToken, brandController.create);
router.put("/:id", verifyToken, brandController.update);
router.delete("/:id", verifyToken, brandController.delete);

export default router;
