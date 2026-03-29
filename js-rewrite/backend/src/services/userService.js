const prisma = require('../prisma');
const bcrypt = require('bcryptjs');

class UserService {
  async getAllUsers() {
    return await prisma.user.findMany({
      include: {
        fakultas: { select: { namaFakultas: true } }
      },
      orderBy: { createdAt: 'desc' }
    });
  }

  async createUser(data) {
    const { name, email, password, level, fakultasId } = data;
    
    // Hash Password
    const hashedPassword = await bcrypt.hash(password, 10);

    return await prisma.user.create({
      data: {
        name,
        email,
        password: hashedPassword,
        level: level || 'admin',
        fakultasId: parseInt(fakultasId),
        status: 'active'
      }
    });
  }

  async updateUser(id, data) {
    const updateData = { ...data };
    if (updateData.password) {
      updateData.password = await bcrypt.hash(updateData.password, 10);
    }
    if (updateData.fakultasId) updateData.fakultasId = parseInt(updateData.fakultasId);

    return await prisma.user.update({
      where: { id: parseInt(id) },
      data: updateData
    });
  }

  async deleteUser(id) {
    return await prisma.user.delete({
      where: { id: parseInt(id) }
    });
  }
}

module.exports = new UserService();
