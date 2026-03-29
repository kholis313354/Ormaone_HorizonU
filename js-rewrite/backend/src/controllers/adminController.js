const adminService = require('../services/adminService');

class AdminController {
  async getStats(req, res) {
    try {
      const stats = await adminService.getDashboardStats();
      res.json({
        status: 'success',
        data: stats
      });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memuat statistik admin' });
    }
  }
}

module.exports = new AdminController();
