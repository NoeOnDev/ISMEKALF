import { Router } from "express";
import { ProviderController } from "../controllers/providerController.js";
import { verifyToken } from "../middleware/authMiddleware.js";

const router = Router();
const providerController = new ProviderController();

router.get("/", verifyToken, providerController.findAll);
router.post("/", verifyToken, providerController.create);
router.put("/:id", verifyToken, providerController.update);
router.delete("/:id", verifyToken, providerController.delete);

export default router;
