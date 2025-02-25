import { UserService } from "../services/user.service.js";

export class UserController {
  constructor(db) {
    this.userService = new UserService(db);
  }

  login = async (req, res) => {
    try {
      const { emailOrUsername, password } = req.body;
      const result = await this.userService.login(emailOrUsername, password);
      res.json({
        success: true,
        ...result,
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        message: error.message,
      });
    }
  };

  register = async (req, res) => {
    try {
      const { username, password, email } = req.body;
      const userId = await this.userService.register({
        username,
        password,
        email,
      });
      res.status(201).json({
        success: true,
        message: "Usuario creado exitosamente",
        userId,
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        message: error.message,
      });
    }
  };

  getWarehouseUsers = async (req, res) => {
    try {
      const users = await this.userService.getWarehouseUsers();
      res.json({ success: true, users });
    } catch (error) {
      res.status(400).json({
        success: false,
        message: error.message,
      });
    }
  };

  updateWarehouseUser = async (req, res) => {
    try {
      const { id } = req.params;
      const { username, password, email } = req.body;
      await this.userService.updateWarehouseUser(id, {
        username,
        password,
        email,
      });
      res.json({
        success: true,
        message: "Usuario de almacén actualizado exitosamente",
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        message: error.message,
      });
    }
  };

  deleteWarehouseUser = async (req, res) => {
    try {
      const { id } = req.params;
      await this.userService.deleteWarehouseUser(id);
      res.json({
        success: true,
        message: "Usuario de almacén eliminado exitosamente",
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        message: error.message,
      });
    }
  };

  refreshToken = async (req, res) => {
    try {
      const { refreshToken } = req.body;
      const result = await this.userService.refreshToken(refreshToken);
      res.json({
        success: true,
        ...result,
      });
    } catch (error) {
      res.status(401).json({
        success: false,
        message: error.message,
      });
    }
  };
}
