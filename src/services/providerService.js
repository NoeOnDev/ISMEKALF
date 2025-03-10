import { Op } from "sequelize";
import { Provider, User } from "../models/indexModels.js";

export class ProviderService {
  async create(providerData, userId) {
    return await Provider.create({
      ...providerData,
      createdBy: userId,
    });
  }

  async update(id, providerData, userId) {
    const provider = await Provider.findByPk(id);
    if (!provider) {
      throw new Error("Proveedor no encontrado");
    }

    return await provider.update({
      ...providerData,
      updatedBy: userId,
    });
  }

  async delete(id) {
    const provider = await Provider.findByPk(id);
    if (!provider) {
      throw new Error("Proveedor no encontrado");
    }

    await provider.destroy();
    return true;
  }

  async findAll(page = 1, limit = 10, search = "") {
    const pageSize = limit;
    const offset = (page - 1) * pageSize;

    const where = search
      ? {
          name: {
            [Op.like]: `%${search}%`,
          },
        }
      : {};

    const { count, rows } = await Provider.findAndCountAll({
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
      providers: rows,
      pagination: {
        total: count,
        currentPage: page,
        totalPages: Math.ceil(count / pageSize),
        pageSize,
      },
    };
  }

  async findById(id) {
    return await Provider.findByPk(id, {
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
