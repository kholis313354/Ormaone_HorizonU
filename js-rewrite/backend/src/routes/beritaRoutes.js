const express = require('express');
const router = express.Router();
const beritaController = require('../controllers/beritaController');

// Routes Berita (Publik)
router.get('/', beritaController.getBeritas);
router.get('/:id', beritaController.getBeritaDetail);

module.exports = router;
