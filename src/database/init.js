import { UserModel } from "../models/user.model.js";

export class DatabaseInitializer {
  constructor(connection) {
    this.connection = connection;
    this.models = {
      userModel: new UserModel(connection),
    };
  }

  async initializeTables() {
    try {
      console.log("📦 Iniciando creación de tablas...");

      await this.models.userModel.createTable();

      console.log("✅ Tablas creadas exitosamente");
    } catch (error) {
      console.error("❌ Error al crear las tablas:", error.message);
      throw error;
    }
  }

  getModels() {
    return this.models;
  }
}
