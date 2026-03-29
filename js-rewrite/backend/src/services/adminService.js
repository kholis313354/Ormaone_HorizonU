const prisma = require('../prisma');

class AdminService {
  async getDashboardStats() {
    const [
      totalUsers,
      totalOrganisasis,
      totalBeritas,
      totalSuara,
      totalFakultas
    ] = await Promise.all([
      prisma.user.count(),
      prisma.organisasi.count(),
      prisma.berita.count(),
      prisma.pemilihanCalonSuara.count(),
      prisma.fakultas.count()
    ]);

    return {
      users: totalUsers,
      organisasis: totalOrganisasis,
      beritas: totalBeritas,
      suara: totalSuara,
      fakultas: totalFakultas
    };
  }
}

module.exports = new AdminService();
