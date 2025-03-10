import { Router } from "express";
import { GroupController } from "../controllers/groupController.js";
import { verifyToken } from "../middleware/authMiddleware.js";

const router = Router();
const groupController = new GroupController();

router.get("/", verifyToken, groupController.findAll);
router.post("/", verifyToken, groupController.create);
router.put("/:id", verifyToken, groupController.update);
router.delete("/:id", verifyToken, groupController.delete);

export default router;
