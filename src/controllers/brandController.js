import { BrandService } from "../services/brandService.js";

const brandService = new BrandService();

export class BrandController {
  async create(req, res) {
    try {
      const { name } = req.body;
      const brand = await brandService.create({ name }, req.user.id);

      res.status(201).json({
        message: "Marca creada exitosamente",
        brand,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al crear la marca",
        error: error.message,
      });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const { name } = req.body;
      const brand = await brandService.update(id, { name }, req.user.id);

      res.json({
        message: "Marca actualizada exitosamente",
        brand,
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al actualizar la marca",
        error: error.message,
      });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await brandService.delete(id);

      res.json({
        message: "Marca eliminada exitosamente",
      });
    } catch (error) {
      res.status(400).json({
        message: "Error al eliminar la marca",
        error: error.message,
      });
    }
  }

  async findAll(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const limit = parseInt(req.query.limit) || 10;
      const search = req.query.search || "";

      const result = await brandService.findAll(page, limit, search);

      res.json(result);
    } catch (error) {
      res.status(400).json({
        message: "Error al obtener las marcas",
        error: error.message,
      });
    }
  }
}
