const votingService = require('../services/votingService');

class VotingController {
  async getActivePemilihans(req, res) {
    try {
      const data = await votingService.getActivePemilihan();
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat daftar pemilihan' });
    }
  }

  async getPublicOverview(req, res) {
    try {
      const data = await votingService.getPublicOverview();
      res.json({ status: 'success', ...data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat data pemilihan' });
    }
  }

  async getDetail(req, res) {
    try {
      const { id } = req.params;
      const data = await votingService.getPemilihanDetail(id);
      if (!data) return res.status(404).json({ status: 'error', message: 'Pemilihan tidak ditemukan' });
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat detail pemilihan' });
    }
  }

  async getStats(req, res) {
    try {
      const { id } = req.params;
      const data = await votingService.getVotingStats(id);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat statistik voting' });
    }
  }

  async requestOtp(req, res) {
    try {
      const data = await votingService.requestOtp(req.body);
      res.json(data);
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mengirim OTP' });
    }
  }

  async submitVote(req, res) {
    try {
      const data = await votingService.submitVote(req.body);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: err.message });
    }
  }

  // Admin Methods
  async createPemilihan(req, res) {
    try {
      const data = await votingService.createPemilihan(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal membuat pemilihan' });
    }
  }

  async updatePemilihan(req, res) {
    try {
      const { id } = req.params;
      const data = await votingService.updatePemilihan(id, req.body);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal memperbarui pemilihan' });
    }
  }

  async deletePemilihan(req, res) {
    try {
      const { id } = req.params;
      await votingService.deletePemilihan(id);
      res.json({ status: 'success', message: 'Pemilihan berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus pemilihan' });
    }
  }

  async createCalon(req, res) {
    try {
      const data = await votingService.createCalon(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal menambah kandidat' });
    }
  }

  async deleteCalon(req, res) {
    try {
      const { id } = req.params;
      await votingService.deleteCalon(id);
      res.json({ status: 'success', message: 'Kandidat berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus kandidat' });
    }
  }
}

module.exports = new VotingController();
