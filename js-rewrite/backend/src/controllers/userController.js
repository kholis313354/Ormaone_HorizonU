const userService = require('../services/userService');

class UserController {
  async list(req, res) {
    try {
      const users = await userService.getAllUsers();
      res.json({ status: 'success', data: users });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal mengambil daftar user' });
    }
  }

  async create(req, res) {
    try {
      const user = await userService.createUser(req.body);
      res.status(201).json({ status: 'success', data: user });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal membuat user' });
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const user = await userService.updateUser(id, req.body);
      res.json({ status: 'success', data: user });
    } catch (err) {
      console.error(err);
      res.status(400).json({ status: 'error', message: 'Gagal memperbarui user' });
    }
  }

  async delete(req, res) {
    try {
      const { id } = req.params;
      await userService.deleteUser(id);
      res.json({ status: 'success', message: 'User berhasil dihapus' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal menghapus user' });
    }
  }
}

module.exports = new UserController();
