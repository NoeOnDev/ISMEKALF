import { DataTypes } from "sequelize";
import { sequelize } from "../_config/db.config.js";
import User from "./User.js";

const Brand = sequelize.define(
  "Brand",
  {
    name: {
      type: DataTypes.STRING,
      allowNull: false,
      unique: true,
      validate: {
        notEmpty: true,
      },
    },
    createdBy: {
      type: DataTypes.INTEGER,
      allowNull: false,
      references: {
        model: User,
        key: "id",
      },
    },
    updatedBy: {
      type: DataTypes.INTEGER,
      allowNull: true,
      references: {
        model: User,
        key: "id",
      },
    },
  },
  {
    timestamps: true,
  }
);

Brand.belongsTo(User, { as: "creator", foreignKey: "createdBy" });
Brand.belongsTo(User, { as: "updater", foreignKey: "updatedBy" });

export default Brand;
