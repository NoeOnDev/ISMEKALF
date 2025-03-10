import { Op } from "sequelize";
import { Brand, User } from "../models/indexModels.js";

export class BrandService {
  async create(brandData, userId) {
    return await Brand.create({
      ...brandData,
      createdBy: userId,
    });
  }

  async update(id, brandData, userId) {
    const brand = await Brand.findByPk(id);
    if (!brand) {
      throw new Error("Marca no encontrada");
    }

    return await brand.update({
      ...brandData,
      updatedBy: userId,
    });
  }

  async delete(id) {
    const brand = await Brand.findByPk(id);
    if (!brand) {
      throw new Error("Marca no encontrada");
    }

    await brand.destroy();
    return true;
  }

  async findAll(page = 1, limit = this.pageSize, search = "") {
    const pageSize = limit;
    const offset = (page - 1) * pageSize;

    const where = search
      ? {
          name: {
            [Op.like]: `%${search}%`,
          },
        }
      : {};

    const { count, rows } = await Brand.findAndCountAll({
      where,
      limit: pageSize,
      offset,
      include: [
        {
          model: User,
          as: "creator",
          attributes: ["username"],
        },
        {
          model: User,
          as: "updater",
          attributes: ["username"],
        },
      ],
      order: [["createdAt", "DESC"]],
    });

    return {
      brands: rows,
      pagination: {
        total: count,
        currentPage: page,
        totalPages: Math.ceil(count / pageSize),
        pageSize,
      },
    };
  }

  async findById(id) {
    return await Brand.findByPk(id, {
      include: [
        {
          model: User,
          as: "creator",
          attributes: ["username"],
        },
        {
          model: User,
          as: "updater",
          attributes: ["username"],
        },
      ],
    });
  }
}
