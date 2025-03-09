import { DataTypes } from "sequelize";
import { sequelize } from "../_config/db.config.js";
import argon2 from "argon2";

const User = sequelize.define(
  "User",
  {
    username: {
      type: DataTypes.STRING,
      allowNull: false,
      unique: true,
      validate: {
        notEmpty: true,
      },
    },
    email: {
      type: DataTypes.STRING,
      allowNull: false,
      unique: true,
      validate: {
        isEmail: true,
      },
    },
    password: {
      type: DataTypes.STRING,
      allowNull: false,
      validate: {
        notEmpty: true,
      },
    },
    role: {
      type: DataTypes.ENUM('coordinador', 'almacen'),
      allowNull: false,
      defaultValue: 'almacen'
    }
  },
  {
    hooks: {
      beforeCreate: async (user) => {
        if (user.password) {
          user.password = await argon2.hash(user.password);
        }
      },
      beforeUpdate: async (user) => {
        if (user.changed("password")) {
          user.password = await argon2.hash(user.password);
        }
      },
    },
  }
);

User.prototype.comparePassword = async function (candidatePassword) {
  return await argon2.verify(this.password, candidatePassword);
};

export default User;
