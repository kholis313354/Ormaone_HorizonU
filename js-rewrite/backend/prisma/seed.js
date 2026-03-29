const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');
const prisma = new PrismaClient();

async function main() {
  const hashedPassword = await bcrypt.hash('admin123456', 12);

  console.log('🌱 Seeding database...');

  // 1. Buat Fakultas default (FICT)
  const fakultas = await prisma.fakultas.upsert({
    where: { id: 1 },
    update: {},
    create: {
      namaFakultas: 'FICT',
    },
  });

  // 2. Buat Organisasi HUSC
  const organisasi = await prisma.organisasi.upsert({
    where: { id: 1 },
    update: {},
    create: {
      name: 'HUSC',
      description: 'Himpunan Mahasiswa Universitas Horizon',
      type: 'BEM',
      fakultasId: fakultas.id,
      image: '',
    },
  });

  // 3. Buat SuperAdmin (level 1)
  const admin = await prisma.user.upsert({
    where: { email: 'admin@ormaone.com' },
    update: {},
    create: {
      name: 'Super Admin',
      email: 'admin@ormaone.com',
      password: hashedPassword,
      level: 1, // SuperAdmin
      status: 'active',
      organisasiId: organisasi.id,
    },
  });

  console.log('✅ Seeding selesai!');
  console.log('------------------');
  console.log('Email Admin: admin@ormaone.com');
  console.log('Password: admin123456');
}

main()
  .catch((e) => {
    console.error('❌ Seeding gagal:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
