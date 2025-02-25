import express from "express";
import cors from "cors";
import { envConfig } from "./config/env.config.js";
import connectWithRetry from "./database/connection.js";
import { DatabaseInitializer } from "./database/init.js";
import { createUserRouter } from "./routes/user.routes.js";

const app = express();
const port = envConfig.port;

app.use(cors());
app.use(express.json());

const startServer = async () => {
  try {
    const connection = await connectWithRetry();

    const dbInitializer = new DatabaseInitializer(connection);
    await dbInitializer.initializeTables();

    app.locals.db = connection;
    app.locals.models = dbInitializer.getModels();

    app.use("/api/users", createUserRouter(connection));

    app.listen(port, () => {
      console.log(`🚀 Servidor corriendo en el puerto ${port}`);
    });
  } catch (error) {
    console.error("❌ Error fatal:", error.message);
    process.exit(1);
  }
};

startServer();

process.on("SIGINT", async () => {
  if (app.locals.db) {
    await app.locals.db.end();
    console.log("Conexión a la base de datos cerrada");
  }
  process.exit(0);
});
