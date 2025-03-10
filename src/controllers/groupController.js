import { GroupService } from "../services/groupService.js";

const groupService = new GroupService();

export class GroupController {
  async create(req, res) {
    try {
      const { name } = req.body;
      const group = await groupService.create({ name }, req.user.id);

      res.status(201).json({
        message: "Grupo creado exitosamente",
        group,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al crear el grupo",
        error: error.message,
      });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const { name } = req.body;
      const group = await groupService.update(id, { name }, req.user.id);

      res.json({
        message: "Grupo actualizado exitosamente",
        group,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al actualizar el grupo",
        error: error.message,
      });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await groupService.delete(id);

      res.json({
        message: "Grupo eliminado exitosamente",
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al eliminar el grupo",
        error: error.message,
      });
    }
  }

  async findAll(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const limit = parseInt(req.query.limit) || 10;
      const search = req.query.search || "";

      const result = await groupService.findAll(page, limit, search);

      res.json(result);
    } catch (error) {
      res.status(400).json({
        message: "Error al obtener los grupos",
        error: error.message,
      });
    }
  }
}
