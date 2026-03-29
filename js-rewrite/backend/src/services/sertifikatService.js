const prisma = require('../prisma');

class SertifikatService {
  async findByNim(nim) {
    return await prisma.sertifikat.findMany({
      where: {
        mahasiswa: {
          nim: nim
        }
      },
      include: {
        namaSertifikat: true,
        mahasiswa: {
          select: {
            nama: true,
            nim: true,
            fakultas: { select: { namaFakultas: true } }
          }
        }
      },
      orderBy: { createdAt: 'desc' }
    });
  }

  async getById(id) {
    return await prisma.sertifikat.findUnique({
      where: { id: parseInt(id) },
      include: {
        namaSertifikat: true,
        mahasiswa: true
      }
    });
  }

  // Admin & Public Methods
  async getAllSertifikat(page = 1, limit = 10, search = '', type = '') {
    const skip = (page - 1) * limit;
    
    // Build where clause
    const where = {};
    if (search) {
      where.OR = [
        { nim: { contains: search, mode: 'insensitive' } },
        { namaPenerima: { contains: search, mode: 'insensitive' } },
        { userName: { contains: search, mode: 'insensitive' } },
      ];
    }
    if (type) {
      where.namaSertifikatId = parseInt(type);
    }

    const [total, data] = await Promise.all([
      prisma.sertifikat.count({ where }),
      prisma.sertifikat.findMany({
        where,
        skip,
        take: limit,
        include: {
          namaSertifikat: true,
          fakultas: { select: { namaFakultas: true } }
        },
        orderBy: { createdAt: 'desc' }
      })
    ]);

    return { 
      total, 
      data, 
      pagination: {
        currentPage: page,
        totalPages: Math.ceil(total / limit),
        perPage: limit,
        totalItems: total
      } 
    };
  }

  async createSertifikat(data) {
    const { nim, nama, email, namaSertifikatId, nomorSertifikat, fileSertifikat } = data;
    
    // Temukan atau buat mahasiswa
    const mahasiswa = await prisma.mahasiswa.upsert({
      where: { nim },
      update: { nama, email },
      create: { nim, nama, email, password: 'password', fakultasId: 1 }
    });

    return await prisma.sertifikat.create({
      data: {
        nomorSertifikat,
        fileSertifikat: fileSertifikat || 'sample.jpg',
        mahasiswaId: mahasiswa.id,
        namaSertifikatId: parseInt(namaSertifikatId)
      }
    });
  }

  async deleteSertifikat(id) {
    return await prisma.sertifikat.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new SertifikatService();
