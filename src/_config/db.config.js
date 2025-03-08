import { Sequelize } from "sequelize";
import { envConfig } from "./env.config.js";

export const sequelize = new Sequelize({
  host: envConfig.db.host,
  port: envConfig.db.port,
  username: envConfig.db.user,
  password: envConfig.db.password,
  database: envConfig.db.name,
  dialect: "mysql",
  logging: false,
});
