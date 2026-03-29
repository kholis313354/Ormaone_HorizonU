const express = require('express');
const router = express.Router();
const sertifikatController = require('../controllers/sertifikatController');

// Routes Sertifikat (Publik)
router.get('/', sertifikatController.searchByNim);
router.get('/:id', sertifikatController.getDetail);

module.exports = router;
