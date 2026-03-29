const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const userController = require('../controllers/userController');
const organisasiController = require('../controllers/organisasiController');
const beritaController = require('../controllers/beritaController');
const votingController = require('../controllers/votingController');
const strukturController = require('../controllers/strukturController');
const sertifikatController = require('../controllers/sertifikatController');
const profileController = require('../controllers/profileController');
const pesanController = require('../controllers/pesanController');
const { authMiddleware, requireLevel } = require('../middlewares/authMiddleware');

// Dashboard Stats (Protected)
router.get('/stats', authMiddleware, requireLevel(1, 2), adminController.getStats);

// Profile Management (Self-service, level 1, 2, 0)
router.put('/profile', authMiddleware, profileController.updateProfile);
router.put('/profile/password', authMiddleware, profileController.updatePassword);

// Pesan & Kontak Masuk (Protected)
router.get('/pesan', authMiddleware, requireLevel(1, 2), pesanController.getAll);
router.get('/pesan/unread', authMiddleware, requireLevel(1, 2), pesanController.getUnreadCount);
router.put('/pesan/:id/read', authMiddleware, requireLevel(1, 2), pesanController.markAsRead);
router.delete('/pesan/:id', authMiddleware, requireLevel(1, 2), pesanController.delete);

// User Management (Protected)
router.get('/users', authMiddleware, requireLevel(1, 2), userController.list);
router.post('/users', authMiddleware, requireLevel(1, 2), userController.create);
router.put('/users/:id', authMiddleware, requireLevel(1, 2), userController.update);
router.delete('/users/:id', authMiddleware, requireLevel(1, 2), userController.delete);

// Organisasi Management (Protected)
router.get('/organisasi', authMiddleware, requireLevel(1, 2), organisasiController.list);
router.post('/organisasi', authMiddleware, requireLevel(1, 2), organisasiController.create);
router.put('/organisasi/:id', authMiddleware, requireLevel(1, 2), organisasiController.update);
router.delete('/organisasi/:id', authMiddleware, requireLevel(1, 2), organisasiController.delete);

// Berita Management (Protected)
router.get('/berita', authMiddleware, requireLevel(1, 2), beritaController.getBeritas);
router.post('/berita', authMiddleware, requireLevel(1, 2), beritaController.create);
router.put('/berita/:id', authMiddleware, requireLevel(1, 2), beritaController.update);
router.delete('/berita/:id', authMiddleware, requireLevel(1, 2), beritaController.delete);

// Voting Management (Protected)
router.get('/voting', authMiddleware, requireLevel(1, 2), votingController.getActivePemilihans);
router.post('/voting', authMiddleware, requireLevel(1, 2), votingController.createPemilihan);
router.put('/voting/:id', authMiddleware, requireLevel(1, 2), votingController.updatePemilihan);
router.delete('/voting/:id', authMiddleware, requireLevel(1, 2), votingController.deletePemilihan);

// Calon Management (Protected)
router.post('/voting/calon', authMiddleware, requireLevel(1, 2), votingController.createCalon);
router.delete('/voting/calon/:id', authMiddleware, requireLevel(1, 2), votingController.deleteCalon);

// Struktur Management (Protected)
router.put('/struktur/:orgId/tampilan', authMiddleware, requireLevel(1, 2), strukturController.updateTampilan);
router.post('/struktur/divisi', authMiddleware, requireLevel(1, 2), strukturController.createDivisi);
router.delete('/struktur/divisi/:id', authMiddleware, requireLevel(1, 2), strukturController.deleteDivisi);

// Sertifikat Management (Protected)
router.get('/sertifikat', authMiddleware, requireLevel(1, 2), sertifikatController.list);
router.post('/sertifikat', authMiddleware, requireLevel(1, 2), sertifikatController.create);
router.delete('/sertifikat/:id', authMiddleware, requireLevel(1, 2), sertifikatController.delete);

module.exports = router;
