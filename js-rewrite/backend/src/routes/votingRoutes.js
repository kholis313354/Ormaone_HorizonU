const express = require('express');
const router = express.Router();
const votingController = require('../controllers/votingController');

// Routes Voting (Publik)
router.get('/', votingController.getActivePemilihans);
router.get('/:id', votingController.getDetail);
router.get('/:id/stats', votingController.getStats);
router.post('/request-otp', votingController.requestOtp);
router.post('/vote', votingController.submitVote);

module.exports = router;
