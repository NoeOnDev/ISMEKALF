import express from "express";
import cors from "cors";
import cookieParser from "cookie-parser";
import { envConfig } from "./_config/env.config.js";
import { sequelize } from "./_config/db.config.js";
import "./models/indexModels.js";
import authRoutes from "./routes/authRoutes.js";
import brandRoutes from "./routes/brandRoutes.js";

const app = express();
const port = envConfig.port;

app.use(
  cors({
    origin: envConfig.client.url,
    credentials: true,
  })
);
app.use(cookieParser());
app.use(express.json());

app.use("/api/auth", authRoutes);
app.use("/api/brands", brandRoutes);

const startServer = async () => {
  try {
    await sequelize.sync({ force: true });
    console.log("✅ Base de datos sincronizada");

    app.listen(port, () => {
      console.log(`🚀 Servidor corriendo en el puerto ${port}`);
    });
  } catch (error) {
    console.error("❌ Error fatal:", error.message);
    process.exit(1);
  }
};

startServer();
