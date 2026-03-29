const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const users = [
    { id: 1, level: 1, name: 'KPUM', email: 'Kholiskamal354@gmail.com', profilePhoto: '1763436752_68d1a5c7671f84e1e041.png', password: '$2y$10$cqpdJr5N48iTgy/ZiEaiyusZSCo9labjLtKASAy9P.2IawgWhVwPS', organisasiId: null, createdAt: new Date('2025-04-19T05:30:25Z'), updatedAt: new Date('2025-11-18T03:32:32Z') },
    { id: 4, level: 2, name: 'HUSC - 2025', email: 'kholiskamaluddinw@gmail.com', profilePhoto: '1763428627_9a276e97beb1f1e00ab3.jpeg', password: '$2y$10$sZPsr5U5J6mml7Yx9VEZIuh8ts9oM50DShlWXnAGiwMUJ7uluX0ae', organisasiId: 8, createdAt: new Date('2025-04-23T02:32:35Z'), updatedAt: new Date('2026-02-01T09:05:43Z') },
    { id: 8, level: 0, name: 'BEM - Fakultas FICT', email: 'bemfict@gmail.com', profilePhoto: '1768987415_d2453c7133c4c9b9b0ef.png', password: '$2y$10$g6RwesxJkYhc5kz4KJBoyexFPoLIsL9Kq9Or/eY9i0iNwTyNnOzpm', organisasiId: 12, createdAt: new Date('2025-04-23T09:45:02Z'), updatedAt: new Date('2026-02-01T01:21:26Z') },
    { id: 50, level: 0, name: 'HIMA', email: 'kholis.wahib.fict@krw.horizon.ac.id', profilePhoto: null, password: '$2y$10$ieU5UfXZr6LkFJPyr0IjguAqIJsDqPp3LMD0yZu5G7wI7x9bF6ICK', organisasiId: 16, createdAt: new Date('2025-12-13T05:26:07Z'), updatedAt: new Date('2025-12-13T05:26:07Z') },
    { id: 51, level: 0, name: 'PR FHS', email: 'admin@gmail.com', profilePhoto: null, password: '$2y$10$h/PTbzZF0hC6Yt/RQTcMBuhcCbYJwRlWTXcj3l9ab2VqMUzcUXE8W', organisasiId: 17, createdAt: new Date('2025-12-13T05:28:22Z'), updatedAt: new Date('2025-12-13T05:28:22Z') },
    { id: 52, level: 0, name: 'test', email: 'test@gmail.com', profilePhoto: null, password: '$2y$10$kfGHeOf1UtaN6P37gvcH7.4aZO/GZRHXd3o9NuHWtCIRtIMGI/Mh2', organisasiId: 16, createdAt: new Date('2026-02-07T13:35:27Z'), updatedAt: new Date('2026-02-07T13:35:27Z') }
  ];

  for (const user of users) {
    try {
      // Pastikan organisasi_id ada jika tidak null
      const dataToUpsert = { ...user };
      if (dataToUpsert.organisasiId !== null) {
        const orgExists = await prisma.organisasi.findUnique({ where: { id: dataToUpsert.organisasiId } });
        if (!orgExists) {
            console.log(`Organisasi ID ${dataToUpsert.organisasiId} belum ada. Membuat placeholder organisasi...`);
            await prisma.organisasi.create({
                data: { id: dataToUpsert.organisasiId, name: 'Imported Organisasi ' + dataToUpsert.organisasiId, description: '', type: 'UKM' }
            });
        }
      }

      const res = await prisma.user.upsert({
        where: { id: user.id },
        update: user,
        create: user
      });
      console.log(`✅ Upserted user: ${res.email}`);
    } catch (err) {
      console.error(`❌ Failed to upsert user ${user.email}:`, err.message);
    }
  }
}

main()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
