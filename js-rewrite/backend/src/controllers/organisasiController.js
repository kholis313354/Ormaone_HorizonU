const organisasiService = require('../services/organisasiService');

class OrganisasiController {
  async list(req, res) {
    try {
      const data = await organisasiService.getAllOrganisasi();
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mengambil daftar organisasi' });
    }
  }

  async create(req, res) {
    try {
      const data = await organisasiService.createOrganisasi(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal membuat organisasi' });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const data = await organisasiService.updateOrganisasi(id, req.body);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal memperbarui organisasi' });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await organisasiService.deleteOrganisasi(id);
      res.json({ status: 'success', message: 'Organisasi berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus organisasi' });
    }
  }
}

module.exports = new OrganisasiController();
