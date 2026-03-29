const prisma = require('../prisma');

/**
 * UserRepository (Data Access Layer)
 * Bertanggung jawab atas semua interaksi database terkait model User.
 * Mirip dengan UserModel.php di CI4.
 */
class UserRepository {
  /**
   * Cari user berdasarkan email.
   * @param {string} email
   * @returns {Promise<object|null>}
   */
  async findByEmail(email) {
    return prisma.user.findUnique({
      where: { email },
      include: { organisasi: { select: { id: true, name: true } } },
    });
  }

  /**
   * Cari user berdasarkan ID.
   * @param {number} id
   * @returns {Promise<object|null>}
   */
  async findById(id) {
    return prisma.user.findUnique({
      where: { id },
      select: {
        id: true,
        name: true,
        email: true,
        level: true,
        profilePhoto: true,
        organisasiId: true,
        status: true,
        createdAt: true,
        organisasi: { select: { id: true, name: true } },
      },
    });
  }

  /**
   * Buat user baru.
   * @param {{ name: string, email: string, password: string, level?: number, organisasiId?: number }} data
   * @returns {Promise<object>}
   */
  async create(data) {
    return prisma.user.create({ data });
  }

  /**
   * Update data user.
   * @param {number} id
   * @param {object} data
   * @returns {Promise<object>}
   */
  async update(id, data) {
    return prisma.user.update({ where: { id }, data });
  }
}

module.exports = new UserRepository();
