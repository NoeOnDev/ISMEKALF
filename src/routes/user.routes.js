import { Router } from "express";
import { UserController } from "../controllers/user.controller.js";

export const createUserRouter = (db) => {
  const router = Router();
  const userController = new UserController(db);

  router.post("/login", userController.login);
  router.post("/register", userController.register);

  return router;
};
