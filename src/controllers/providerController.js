import { ProviderService } from "../services/providerService.js";

const providerService = new ProviderService();

export class ProviderController {
  async create(req, res) {
    try {
      const { name } = req.body;
      const provider = await providerService.create({ name }, req.user.id);

      res.status(201).json({
        message: "Proveedor creado exitosamente",
        provider,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al crear el proveedor",
        error: error.message,
      });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const { name } = req.body;
      const provider = await providerService.update(id, { name }, req.user.id);

      res.json({
        message: "Proveedor actualizado exitosamente",
        provider,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al actualizar el proveedor",
        error: error.message,
      });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await providerService.delete(id);

      res.json({
        message: "Proveedor eliminado exitosamente",
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al eliminar el proveedor",
        error: error.message,
      });
    }
  }

  async findAll(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const limit = parseInt(req.query.limit) || 10;
      const search = req.query.search || "";

      const result = await providerService.findAll(page, limit, search);

      res.json(result);
    } catch (error) {
      res.status(400).json({
        message: "Error al obtener los proveedores",
        error: error.message,
      });
    }
  }
}
