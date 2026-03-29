const prisma = require('../prisma');

// Fakultas Controllers
exports.getFakultas = async (req, res, next) => {
    try {
        const fakultas = await prisma.fakultas.findMany();
        res.status(200).json({ success: true, data: fakultas });
    } catch (err) { next(err); }
};

exports.createFakultas = async (req, res, next) => {
    try {
        const { namaFakultas } = req.body;
        const newFak = await prisma.fakultas.create({ data: { namaFakultas } });
        res.status(201).json({ success: true, data: newFak });
    } catch (err) { next(err); }
};

exports.updateFakultas = async (req, res, next) => {
    try {
        const { id } = req.params;
        const { namaFakultas } = req.body;
        const updatedFak = await prisma.fakultas.update({
            where: { id: parseInt(id) },
            data: { namaFakultas }
        });
        res.status(200).json({ success: true, data: updatedFak });
    } catch (err) { next(err); }
};

exports.deleteFakultas = async (req, res, next) => {
    try {
        const { id } = req.params;
        await prisma.fakultas.delete({ where: { id: parseInt(id) } });
        res.status(200).json({ success: true, message: 'Fakultas berhasil dihapus' });
    } catch (err) { next(err); }
};

// Organisasi Controllers
exports.getOrganisasi = async (req, res, next) => {
    try {
        const orgs = await prisma.organisasi.findMany({ include: { fakultas: true } });
        res.status(200).json({ success: true, data: orgs });
    } catch (err) { next(err); }
};

exports.createOrganisasi = async (req, res, next) => {
    try {
        const { name, description, type, fakultasId, kodeFakultas } = req.body;
        const image = req.file ? req.file.filename : (req.body.image || '');
        const newOrg = await prisma.organisasi.create({
            data: { name, description, type, image, fakultasId: fakultasId ? parseInt(fakultasId) : null, kodeFakultas }
        });
        res.status(201).json({ success: true, data: newOrg });
    } catch (err) { next(err); }
};

exports.updateOrganisasi = async (req, res, next) => {
    try {
        const { id } = req.params;
        const { name, description, type, fakultasId, kodeFakultas } = req.body;
        
        let dataToUpdate = { name, description, type };
        
        if (req.file) {
            dataToUpdate.image = req.file.filename;
        } else if (req.body.image !== undefined) {
            dataToUpdate.image = req.body.image;
        }

        if (fakultasId !== undefined && fakultasId !== '') dataToUpdate.fakultasId = parseInt(fakultasId);
        if (fakultasId === '') dataToUpdate.fakultasId = null;
        if (kodeFakultas !== undefined) dataToUpdate.kodeFakultas = kodeFakultas;

        const updatedOrg = await prisma.organisasi.update({
            where: { id: parseInt(id) },
            data: dataToUpdate
        });
        res.status(200).json({ success: true, data: updatedOrg });
    } catch (err) { next(err); }
};

exports.deleteOrganisasi = async (req, res, next) => {
    try {
        const { id } = req.params;
        await prisma.organisasi.delete({ where: { id: parseInt(id) } });
        res.status(200).json({ success: true, message: 'Organisasi berhasil dihapus' });
    } catch (err) { next(err); }
};
