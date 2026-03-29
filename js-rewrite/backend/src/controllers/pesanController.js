const prisma = require('../prisma');

class PesanController {
  async getAll(req, res) {
    try {
      const messages = await prisma.pesan.findMany({ orderBy: { createdAt: 'desc' } });
      res.json({ status: 'success', data: messages });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mengambil pesan masukan' });
    }
  }

  async getUnreadCount(req, res) {
    try {
      const count = await prisma.pesan.count({ where: { status: 'unread' } });
      res.json({ status: 'success', data: { count } });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghitung pesan masuk' });
    }
  }

  async markAsRead(req, res) {
    try {
      const { id } = req.params;
      const updated = await prisma.pesan.update({
        where: { id: parseInt(id) },
        data: { status: 'read' }
      });
      res.json({ status: 'success', data: updated });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menandai pesan' });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await prisma.pesan.delete({ where: { id: parseInt(id) } });
      res.json({ status: 'success', message: 'Pesan berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus pesan' });
    }
  }
}

module.exports = new PesanController();
