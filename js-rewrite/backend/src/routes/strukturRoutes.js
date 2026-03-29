const express = require('express');
const router = express.Router();
const strukturController = require('../controllers/strukturController');
const prisma = require('../prisma');

// Get All Organisasi (untuk dropdown/pilihan)
router.get('/', async (req, res) => {
  try {
    const data = await prisma.organisasi.findMany({
      include: { fakultas: { select: { namaFakultas: true } } }
    });
    res.json({ status: 'success', data });
  } catch (err) {
    res.status(500).json({ status: 'error', message: err.message });
  }
});

// Get Struktur by Org ID
router.get('/:orgId', strukturController.getStruktur);

module.exports = router;
