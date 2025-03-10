import { Op } from "sequelize";
import { Group, User } from "../models/indexModels.js";

export class GroupService {
  async create(groupData, userId) {
    return await Group.create({
      ...groupData,
      createdBy: userId,
    });
  }

  async update(id, groupData, userId) {
    const group = await Group.findByPk(id);
    if (!group) {
      throw new Error("Grupo no encontrado");
    }

    return await group.update({
      ...groupData,
      updatedBy: userId,
    });
  }

  async delete(id) {
    const group = await Group.findByPk(id);
    if (!group) {
      throw new Error("Grupo no encontrado");
    }

    await group.destroy();
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

    const { count, rows } = await Group.findAndCountAll({
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
      groups: rows,
      pagination: {
        total: count,
        currentPage: page,
        totalPages: Math.ceil(count / pageSize),
        pageSize,
      },
    };
  }

  async findById(id) {
    return await Group.findByPk(id, {
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
