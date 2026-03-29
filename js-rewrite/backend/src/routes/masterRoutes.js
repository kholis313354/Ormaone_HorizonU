const express = require('express');
const router = express.Router();
const masterController = require('../controllers/masterController');
const authMiddleware = require('../middlewares/authMiddleware').authMiddleware;
const requireLevel = require('../middlewares/authMiddleware').requireLevel;
const upload = require('../middlewares/uploadMiddleware');

// Public route for public display
router.get('/fakultas', masterController.getFakultas);
router.get('/organisasi', masterController.getOrganisasi);

// Protected Admin route (Hanya level 1 & 2)
router.post('/fakultas', authMiddleware, requireLevel(1, 2), masterController.createFakultas);
router.put('/fakultas/:id', authMiddleware, requireLevel(1, 2), masterController.updateFakultas);
router.delete('/fakultas/:id', authMiddleware, requireLevel(1, 2), masterController.deleteFakultas);

router.post('/organisasi', authMiddleware, requireLevel(1, 2), upload.single('image'), masterController.createOrganisasi);
router.put('/organisasi/:id', authMiddleware, requireLevel(1, 2), upload.single('image'), masterController.updateOrganisasi);
router.delete('/organisasi/:id', authMiddleware, requireLevel(1, 2), masterController.deleteOrganisasi);

module.exports = router;
