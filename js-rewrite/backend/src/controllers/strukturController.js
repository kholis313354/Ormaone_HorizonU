const strukturService = require('../services/strukturService');

class StrukturController {
  async getStruktur(req, res) {
    try {
      const orgId = req.params.orgId || req.query.org;
      const { tahun } = req.query;

      if (!orgId) return res.status(400).json({ status: 'error', message: 'Parameter org diperlukan' });
      
      const { tampilan, divisi } = await strukturService.getStrukturByOrg(orgId, tahun);
      const organisasi = await strukturService.getOrganisasiDetail(orgId);

      if (!organisasi) return res.status(404).json({ status: 'error', message: 'Organisasi tidak ditemukan' });

      res.json({
        status: 'success',
        data: {
          organisasi,
          tampilan,
          divisi
        }
      });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat struktur organisasi' });
    }
  }

  async updateTampilan(req, res) {
    try {
      const { orgId } = req.params;
      const data = await strukturService.updateTampilan(orgId, req.body);
      res.json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal memperbarui tampilan struktur' });
    }
  }

  async createDivisi(req, res) {
    try {
      const data = await strukturService.createDivisi(req.body);
      res.status(201).json({ status: 'success', data });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal menambah divisi' });
    }
  }

  async deleteDivisi(req, res) {
    try {
      const { id } = req.params;
      await strukturService.deleteDivisi(id);
      res.json({ status: 'success', message: 'Divisi berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus divisi' });
    }
  }
}

module.exports = new StrukturController();
