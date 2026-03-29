const prisma = require('../prisma');

class StrukturService {
  async getStrukturByOrg(orgId, tahun = null) {
    const where = { organisasiId: parseInt(orgId) };
    if (tahun) where.tahun = tahun;

    const [tampilan, divisi] = await Promise.all([
      prisma.strukturTampilan.findFirst({
        where,
        orderBy: { createdAt: 'desc' }
      }),
      prisma.strukturTampilanDivisi.findMany({
        where,
        orderBy: { id: 'asc' }
      })
    ]);

    return { tampilan, divisi };
  }

  async getOrganisasiDetail(orgId) {
    return await prisma.organisasi.findUnique({
      where: { id: parseInt(orgId) },
      include: {
        fakultas: { select: { namaFakultas: true } }
      }
    });
  }

  // Admin Methods
  async updateTampilan(orgId, data) {
    const { jabatan1, nama1, jabatan2, nama2, jabatan3, nama3, jabatan4, nama4, tahun } = data;
    return await prisma.strukturTampilan.upsert({
      where: { organisasiId: parseInt(orgId) },
      update: { jabatan1, nama1, jabatan2, nama2, jabatan3, nama3, jabatan4, nama4, tahun },
      create: { 
        organisasiId: parseInt(orgId), 
        jabatan1, nama1, jabatan2, nama2, jabatan3, nama3, jabatan4, nama4, tahun 
      }
    });
  }

  async createDivisi(data) {
    const { namaDivisi, namaAnggota, organisasiId, tahun } = data;
    return await prisma.strukturTampilanDivisi.create({
      data: {
        namaDivisi,
        namaAnggota,
        organisasiId: parseInt(organisasiId),
        tahun
      }
    });
  }

  async deleteDivisi(id) {
    return await prisma.strukturTampilanDivisi.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new StrukturService();
