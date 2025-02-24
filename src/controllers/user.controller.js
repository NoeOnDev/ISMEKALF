import { UserService } from "../services/user.service.js";

export class UserController {
  constructor(db) {
    this.userService = new UserService(db);
  }

  login = async (req, res) => {
    try {
      const { username, password } = req.body;
      const user = await this.userService.login(username, password);
      res.json({ success: true, user });
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
}
