const prisma = require('../prisma');

class BeritaService {
  async getAllBerita(page = 1, limit = 10, search = '', category = '') {
    const skip = (page - 1) * limit;
    
    const where = {
      status: 'published',
      AND: [
        search ? { namaKegiatan: { contains: search, mode: 'insensitive' } } : {},
        category ? { kategori: category } : {}
      ]
    };

    const [total, data] = await Promise.all([
      prisma.berita.count({ where }),
      prisma.berita.findMany({
        where,
        skip,
        take: limit,
        orderBy: { tanggal: 'desc' },
        include: {
          fakultas: {
            select: { namaFakultas: true }
          }
        }
      })
    ]);

    return {
      total,
      page,
      limit,
      totalPages: Math.ceil(total / limit),
      data
    };
  }

  async getBeritaById(id) {
    return await prisma.berita.findUnique({
      where: { id: parseInt(id) },
      include: {
        fakultas: { select: { namaFakultas: true } },
        user: { select: { name: true } }
      }
    });
  }

  async createBerita(data) {
    const { namaKegiatan, deskripsi, tanggal, kategori, fakultasId, userId } = data;
    return await prisma.berita.create({
      data: {
        namaKegiatan,
        deskripsi,
        tanggal: new Date(tanggal),
        kategori,
        fakultasId: parseInt(fakultasId),
        userId: parseInt(userId),
        status: 'published' // Default
      }
    });
  }

  async updateBerita(id, data) {
    const updateData = { ...data };
    if (updateData.tanggal) updateData.tanggal = new Date(updateData.tanggal);
    if (updateData.fakultasId) updateData.fakultasId = parseInt(updateData.fakultasId);
    if (updateData.userId) updateData.userId = parseInt(updateData.userId);

    return await prisma.berita.update({
      where: { id: parseInt(id) },
      data: updateData
    });
  }

  async deleteBerita(id) {
    return await prisma.berita.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new BeritaService();
