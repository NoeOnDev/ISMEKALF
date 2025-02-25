import { Router } from "express";
import { UserController } from "../controllers/user.controller.js";
import {
  authenticateToken,
  isCoordinator,
} from "../middlewares/auth.middleware.js";

export const createUserRouter = (db) => {
  const router = Router();
  const userController = new UserController(db);

  router.post("/login", userController.login);
  router.post("/register", userController.register);
  router.post("/refresh-token", userController.refreshToken);

  router.use(authenticateToken);
  router.get("/warehouse", isCoordinator, userController.getWarehouseUsers);
  router.put(
    "/warehouse/:id",
    isCoordinator,
    userController.updateWarehouseUser
  );
  router.delete(
    "/warehouse/:id",
    isCoordinator,
    userController.deleteWarehouseUser
  );

  return router;
};
