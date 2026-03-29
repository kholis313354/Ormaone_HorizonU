const sertifikatService = require('../services/sertifikatService');

class SertifikatController {
  async searchByNim(req, res) {
    try {
      const { nim } = req.query;
      if (!nim) return res.status(400).json({ status: 'error', message: 'NIM diperlukan' });

      const data = await sertifikatService.findByNim(nim);
      res.json({
        status: 'success',
        data
      });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mencari sertifikat' });
    }
  }

  async getDetail(req, res) {
    try {
      const { id } = req.params;
      const data = await sertifikatService.getById(id);
      if (!data) return res.status(404).json({ status: 'error', message: 'Sertifikat tidak ditemukan' });
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat detail sertifikat' });
    }
  }

  async list(req, res) {
    try {
      const { page, limit, search, type } = req.query;
      const result = await sertifikatService.getAllSertifikat(
        parseInt(page) || 1, 
        parseInt(limit) || 12, // 12 items per page for public UI
        search || '',
        type || ''
      );
      res.json({ status: 'success', ...result });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mengambil daftar sertifikat' });
    }
  }

  async create(req, res) {
    try {
      const data = await sertifikatService.createSertifikat(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal membuat sertifikat' });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await sertifikatService.deleteSertifikat(id);
      res.json({ status: 'success', message: 'Sertifikat berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus sertifikat' });
    }
  }
}

module.exports = new SertifikatController();
