require('dotenv').config();
const express = require('express');
const cors = require('cors');
const authRoutes = require('./routes/authRoutes');
const masterRoutes = require('./routes/masterRoutes');
const beritaRoutes = require('./routes/beritaRoutes');
const strukturRoutes = require('./routes/strukturRoutes');
const sertifikatRoutes = require('./routes/sertifikatRoutes');
const votingRoutes = require('./routes/votingRoutes');
const adminRoutes = require('./routes/adminRoutes');
const publicRoutes = require('./routes/publicRoutes');
const { errorHandler } = require('./middlewares/errorMiddleware');

const app = express();

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const path = require('path');
app.use('/uploads', express.static(path.join(__dirname, '../../../uploads')));

// Routes API
app.use('/api/auth', authRoutes);
app.use('/api/master', masterRoutes);
app.use('/api/berita', beritaRoutes);
app.use('/api/struktur', strukturRoutes);
app.use('/api/sertifikat', sertifikatRoutes);
app.use('/api/voting', votingRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/public', publicRoutes);

// General Root endpoint
app.get('/', (req, res) => {
    res.json({ message: "Welcome to Ormaone JS API" });
});

// Middleware Error handling (harus diletakkan di bagian paling bawah)
app.use(errorHandler);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`🚀 Server running on port ${PORT}`);
});
