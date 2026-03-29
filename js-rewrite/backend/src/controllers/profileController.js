const prisma = require('../prisma');
const bcrypt = require('bcryptjs');

class ProfileController {
  async updateProfile(req, res) {
    try {
      const userId = req.user.id;
      const { name, email } = req.body;

      if (!name || !email) {
        return res.status(400).json({ status: 'error', message: 'Nama dan Email harus diisi' });
      }

      // Check if email already exists for another user
      const existingUser = await prisma.user.findFirst({
        where: { email, id: { not: userId } }
      });
      if (existingUser) {
        return res.status(400).json({ status: 'error', message: 'Email sudah terpakai oleh pengguna lain' });
      }

      const updatedUser = await prisma.user.update({
        where: { id: userId },
        data: { name, email },
        select: { id: true, name: true, email: true, level: true, profilePhoto: true }
      });

      res.json({ status: 'success', data: updatedUser, message: 'Profil berhasil diperbarui' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memperbarui profil' });
    }
  }

  async updatePassword(req, res) {
    try {
      const userId = req.user.id;
      const { currentPassword, newPassword } = req.body;

      if (!currentPassword || !newPassword) {
        return res.status(400).json({ status: 'error', message: 'Password saat ini dan password baru harus diisi' });
      }
      if (newPassword.length < 8) {
        return res.status(400).json({ status: 'error', message: 'Password baru minimal 8 karakter' });
      }

      const user = await prisma.user.findUnique({ where: { id: userId } });
      if (!user) return res.status(404).json({ status: 'error', message: 'User tidak ditemukan' });

      const isMatch = await bcrypt.compare(currentPassword, user.password);
      if (!isMatch) {
        return res.status(400).json({ status: 'error', message: 'Password saat ini salah' });
      }

      const hashedPassword = await bcrypt.hash(newPassword, 12);
      await prisma.user.update({
        where: { id: userId },
        data: { password: hashedPassword }
      });

      res.json({ status: 'success', message: 'Password berhasil diperbarui' });
    } catch (err) {
      console.error(err);
      res.status(500).json({ status: 'error', message: 'Gagal memperbarui password' });
    }
  }
}

module.exports = new ProfileController();
