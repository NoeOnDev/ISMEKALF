import mysql from "mysql2/promise";
import { envConfig } from "../config/env.config.js";

const createConnection = async () => {
  try {
    const connection = await mysql.createConnection({
      host: envConfig.db.host,
      user: envConfig.db.user,
      password: envConfig.db.password,
      database: envConfig.db.name,
      port: envConfig.db.port,
    });

    console.log("🚀 Conexión exitosa con la base de datos");
    return connection;
  } catch (error) {
    console.error("❌ Error al conectar con la base de datos:", error.message);
    throw error;
  }
};

const connectWithRetry = async (retries = 5, delay = 5000) => {
  for (let i = 0; i < retries; i++) {
    try {
      return await createConnection();
    } catch (error) {
      if (i === retries - 1) throw error;
      console.log(`Reintentando conexión en ${delay / 1000} segundos...`);
      await new Promise((resolve) => setTimeout(resolve, delay));
    }
  }
};

export default connectWithRetry;
