const beritaService = require('../services/beritaService');

class BeritaController {
  async getBeritas(req, res) {
    try {
      const { page, limit, q, category } = req.query;
      const result = await beritaService.getAllBerita(
        parseInt(page) || 1,
        parseInt(limit) || 10,
        q || '',
        category || ''
      );
      res.json({
        status: 'success',
        ...result
      });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat berita' });
    }
  }

  async getBeritaDetail(req, res) {
    try {
      const { id } = req.params;
      const data = await beritaService.getBeritaById(id);
      if (!data) return res.status(404).json({ status: 'error', message: 'Berita tidak ditemukan' });
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat detail berita' });
    }
  }

  async create(req, res) {
    try {
      const data = await beritaService.createBerita(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal membuat berita' });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const data = await beritaService.updateBerita(id, req.body);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal memperbarui berita' });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await beritaService.deleteBerita(id);
      res.json({ status: 'success', message: 'Berita berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus berita' });
    }
  }
}

module.exports = new BeritaController();
