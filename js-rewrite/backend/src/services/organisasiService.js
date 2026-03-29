const prisma = require('../prisma');

class OrganisasiService {
  async getAllOrganisasi() {
    return await prisma.organisasi.findMany({
      include: {
        fakultas: { select: { namaFakultas: true } }
      },
      orderBy: { id: 'asc' }
    });
  }

  async createOrganisasi(data) {
    const { namaOrganisasi, fakultasId, anggaran } = data;
    return await prisma.organisasi.create({
      data: {
        namaOrganisasi,
        fakultasId: parseInt(fakultasId),
        anggaran: parseFloat(anggaran || 0),
        anggaranTerpakai: 0
      }
    });
  }

  async updateOrganisasi(id, data) {
    const updateData = { ...data };
    if (updateData.fakultasId) updateData.fakultasId = parseInt(updateData.fakultasId);
    if (updateData.anggaran) updateData.anggaran = parseFloat(updateData.anggaran);

    return await prisma.organisasi.update({
      where: { id: parseInt(id) },
      data: updateData
    });
  }

  async deleteOrganisasi(id) {
    return await prisma.organisasi.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new OrganisasiService();
