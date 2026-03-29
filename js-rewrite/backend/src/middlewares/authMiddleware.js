const jwt = require('jsonwebtoken');

const authMiddleware = (req, res, next) => {
  const authHeader = req.headers.authorization;
  
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return res.status(401).json({ status: 'error', message: 'Akses ditolak. Token tidak ditemukan.' });
  }

  const token = authHeader.split(' ')[1];

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'ormaone_secret_key');
    req.user = decoded; // Menyimpan data user (id, role, dll) ke request
    next();
  } catch (err) {
    return res.status(403).json({ status: 'error', message: 'Token tidak valid atau telah kedaluwarsa.' });
  }
};

const adminOnly = (req, res, next) => {
  if (req.user && req.user.role === 'admin') {
    next();
  } else {
    res.status(403).json({ status: 'error', message: 'Hanya Admin yang diizinkan mengakses rute ini.' });
  }
};

const requireLevel = (...levels) => {
  return (req, res, next) => {
    if (req.user && levels.includes(req.user.level)) {
      next();
    } else {
      res.status(403).json({ status: 'error', message: 'Akses ditolak. Level Anda tidak diizinkan mengakses rute ini.' });
    }
  };
};

module.exports = { authMiddleware, adminOnly, requireLevel };
