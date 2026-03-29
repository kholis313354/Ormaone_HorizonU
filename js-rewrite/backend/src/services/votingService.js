const prisma = require('../prisma');

class VotingService {
  async getActivePemilihan() {
    return await prisma.pemilihan.findMany({
      where: {
        status: 'active'
      },
      include: {
        organisasi: { select: { namaOrganisasi: true } }
      },
      orderBy: { createdAt: 'desc' }
    });
  }

  async getPublicOverview() {
    // Replica of CI4 Home.php voting() method logic for the public dashboard
    // 1. Get all active & published pemilihans
    const pemilihans = await prisma.pemilihan.findMany({
      where: { status: 1 }, // Assuming 1 is publish/active in CI4
      orderBy: { endDate: 'desc' }
    });

    let isSelesai = false;
    let pemilihan = null;

    if (pemilihans.length === 0) {
      // If no active, check for finished
      const pemilihanSelesai = await prisma.pemilihan.findFirst({
        where: { status: 2 }, // Assuming 2 is finished
        orderBy: { createdAt: 'desc' }
      });
      isSelesai = pemilihanSelesai !== null;
      pemilihan = pemilihanSelesai;
    } else {
      pemilihan = pemilihans[0];
    }

    let calon = [];
    let totalSuaras = {};

    if (pemilihan) {
      calon = await prisma.pemilihanCalon.findMany({
        where: { pemilihanId: pemilihan.id },
        include: {
          ketua: true,
          wakil: true,
          pemilihan: { include: { organisasi: true } }
        },
        orderBy: { noUrut: 'asc' }
      });

      // Calculate votes
      const voteCounts = await prisma.pemilihanCalonSuara.groupBy({
        by: ['pemilihanCalonId'],
        where: { status: 1, calon: { pemilihanId: pemilihan.id } },
        _count: { pemilihanCalonId: true }
      });

      const voteMap = {};
      voteCounts.forEach(vc => {
        voteMap[vc.pemilihanCalonId] = vc._count.pemilihanCalonId;
      });

      calon.forEach(c => {
        const total = voteMap[c.id] || 0;
        const ketName = c.ketua ? c.ketua.name : '';
        const wakName = c.wakil ? c.wakil.name : '';
        const nameKey = wakName ? `${ketName} & ${wakName}` : ketName;
        totalSuaras[nameKey] = total;
      });
    }

    return {
      calon,
      totalSuaras,
      pemilihan: pemilihan ? Object.assign(pemilihan, { tanggal_akhir: pemilihan.endDate, tanggal_mulai: pemilihan.startDate }) : null,
      isSelesai,
      pemilihans
    };
  }

  async getPemilihanDetail(id) {
    return await prisma.pemilihan.findUnique({
      where: { id: parseInt(id) },
      include: {
        organisasi: true,
        pemilihanCalons: {
          include: {
            _count: {
              select: { pemilihanCalonSuaras: true }
            }
          },
          orderBy: { noUrut: 'asc' }
        }
      }
    });
  }

  async getVotingStats(pemilihanId) {
    const totalSuara = await prisma.pemilihanCalonSuara.count({
      where: { pemilihanId: parseInt(pemilihanId) }
    });

    const candidates = await prisma.pemilihanCalon.findMany({
      where: { pemilihanId: parseInt(pemilihanId) },
      select: {
        id: true,
        nama: true,
        noUrut: true,
        _count: {
          select: { pemilihanCalonSuaras: true }
        }
      }
    });

    return { totalSuara, candidates };
  }

  async requestOtp(data) {
    const { nim, email, pemilihanId } = data;
    // Mock OTP Generation
    const otp = "123456"; 
    console.log(`[MOCK OTP] Terkirim ke ${email} untuk NIM ${nim}: ${otp}`);
    
    // Simpan ke DB sementara atau session? 
    // Untuk demo, kita anggap OTP 123456 selalu valid.
    return { success: true, message: 'OTP terkirim (Mock)' };
  }

  async submitVote(data) {
    const { nim, nama, email, otp, pemilihanId, calonId } = data;

    // Validasi OTP
    if (otp !== "123456") throw new Error("OTP tidak valid");

    // Cek apakah sudah memilih di pemilihan ini
    const existingVote = await prisma.pemilihanCalonSuara.findFirst({
      where: {
        pemilihanId: parseInt(pemilihanId),
        mahasiswa: {
          nim: nim
        }
      }
    });

    if (existingVote) throw new Error("Anda sudah menggunakan hak suara dalam pemilihan ini");

    // Masukkan Suara
    return await prisma.pemilihanCalonSuara.create({
      data: {
        pemilihanId: parseInt(pemilihanId),
        calonId: parseInt(calonId),
        mahasiswa: {
          connectOrCreate: {
            where: { nim: nim },
            create: {
              nama: nama,
              nim: nim,
              email: email,
              password: 'default_voter_pass', // Placeholder
              fakultasId: 1 // Default atau lookup dari NIM
            }
          }
        }
      }
    });
  }

  // Admin Methods
  async createPemilihan(data) {
    const { judul, deskripsi, tanggalMulai, tanggalSelesai, organisasiId } = data;
    return await prisma.pemilihan.create({
      data: {
        judul,
        deskripsi,
        tanggalMulai: new Date(tanggalMulai),
        tanggalSelesai: new Date(tanggalSelesai),
        organisasiId: parseInt(organisasiId),
        status: 'active'
      }
    });
  }

  async updatePemilihan(id, data) {
    const updateData = { ...data };
    if (updateData.tanggalMulai) updateData.tanggalMulai = new Date(updateData.tanggalMulai);
    if (updateData.tanggalSelesai) updateData.tanggalSelesai = new Date(updateData.tanggalSelesai);
    if (updateData.organisasiId) updateData.organisasiId = parseInt(updateData.organisasiId);

    return await prisma.pemilihan.update({
      where: { id: parseInt(id) },
      data: updateData
    });
  }

  async deletePemilihan(id) {
    return await prisma.pemilihan.delete({
      where: { id: parseInt(id) }
    });
  }

  async createCalon(data) {
    const { pemilihanId, nama, noUrut, visiMisi, gambar } = data;
    return await prisma.pemilihanCalon.create({
      data: {
        pemilihanId: parseInt(pemilihanId),
        nama,
        noUrut: parseInt(noUrut),
        visiMisi,
        gambar
      }
    });
  }

  async deleteCalon(id) {
    return await prisma.pemilihanCalon.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new VotingService();
