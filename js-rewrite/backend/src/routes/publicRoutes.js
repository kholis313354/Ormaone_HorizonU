const express = require('express');
const router = express.Router();
const masterController = require('../controllers/masterController');
const beritaController = require('../controllers/beritaController');
const strukturController = require('../controllers/strukturController');
const sertifikatController = require('../controllers/sertifikatController');
const votingController = require('../controllers/votingController');
const prisma = require('../prisma');

// 1. Organisasi (untuk HomePage slider)
router.get('/organisasi', masterController.getOrganisasi);

// 2. Berita (untuk BeritaPage)
router.get('/berita', beritaController.getBeritas);
router.get('/berita/:id', beritaController.getBeritaDetail);

// 3. Struktur (untuk StrukturPage)
router.get('/struktur', strukturController.getStruktur);

// 4. Sertifikat (untuk SertifikatPage - search public)
router.get('/sertifikat', sertifikatController.list);

// 5. Voting (untuk E-Voting Public)
router.get('/voting', votingController.getPublicOverview);

// 6. Contact Form (Landing page)
router.post('/contact', async (req, res) => {
    try {
        const { name, email, subject, message } = req.body;
        if (!name || !email || !message) {
            return res.status(400).json({ status: 'error', message: 'Semua kolom wajib diisi' });
        }
        await prisma.pesan.create({
            data: { name, email, subject: subject || 'Tanpa Subjek', message, status: 'unread' }
        });
        res.status(201).json({ status: 'success', message: 'Pesan berhasil dikirim' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Gagal mengirim pesan' });
    }
});

module.exports = router;
