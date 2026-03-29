const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const UserRepository = require('../repositories/UserRepository');

const JWT_SECRET = process.env.JWT_SECRET || 'OrmaOneSecretHashSignKunci@!26';
const JWT_EXPIRES_IN = process.env.JWT_EXPIRES_IN || '1d';

/**
 * AuthService (Business Logic Layer)
 * Berisi logika bisnis autentikasi: login, register, validasi token.
 * Setara dengan Auth.php Controller di CI4.
 */
class AuthService {
  /**
   * Login user. Cocok dengan fungsi Auth::login() di CI4.
   * Validasi email & password, lalu kembalikan JWT token.
   *
   * @param {string} email
   * @param {string} password
   * @returns {Promise<{ token: string, user: object }>}
   * @throws {Error} jika email tidak ditemukan atau password salah
   */
  async login(email, password) {
    // Validasi sederhana (validasi rinci ada di layer controller/middleware)
    if (!email || !password) {
      const err = new Error('Email dan password harus diisi.');
      err.statusCode = 400;
      throw err;
    }

    // Cek apakah user ada di database (seperti where('email', $data['email'])->first() di CI4)
    const user = await UserRepository.findByEmail(email);
    if (!user) {
      const err = new Error('Email atau password salah.');
      err.statusCode = 401;
      throw err;
    }

    // Cek status aktif user
    if (user.status !== 'active') {
      const err = new Error('Akun Anda telah dinonaktifkan. Hubungi administrator.');
      err.statusCode = 403;
      throw err;
    }

    // Verifikasi password dengan bcrypt (seperti password_verify() di PHP)
    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) {
      const err = new Error('Email atau password salah.');
      err.statusCode = 401;
      throw err;
    }

    // Generate JWT Token (seperti session()->set() di CI4, tapi stateless)
    const payload = {
      id: user.id,
      email: user.email,
      level: user.level,
      organisasiId: user.organisasiId,
    };

    const token = jwt.sign(payload, JWT_SECRET, { expiresIn: JWT_EXPIRES_IN });

    // User data yang aman untuk dikirim ke frontend (tanpa password)
    const safeUser = {
      id: user.id,
      name: user.name,
      email: user.email,
      level: user.level,
      profilePhoto: user.profilePhoto,
      organisasiId: user.organisasiId,
      organisasi: user.organisasi,
    };

    return { token, user: safeUser };
  }

  /**
   * Register / Daftar user baru.
   *
   * @param {{ name: string, email: string, password: string, level?: number }} data
   * @returns {Promise<object>} user baru (tanpa password)
   * @throws {Error} jika email sudah terdaftar
   */
  async register({ name, email, password, level = 0 }) {
    // Cek email sudah ada
    const existing = await UserRepository.findByEmail(email);
    if (existing) {
      const err = new Error('Email sudah terdaftar.');
      err.statusCode = 409;
      throw err;
    }

    // Hash password dengan bcrypt (cost factor 12)
    const hashedPassword = await bcrypt.hash(password, 12);

    const newUser = await UserRepository.create({
      name,
      email,
      password: hashedPassword,
      level,
    });

    return { id: newUser.id, name: newUser.name, email: newUser.email };
  }

  /**
   * Verifikasi JWT Token dan kembalikan payload-nya.
   *
   * @param {string} token
   * @returns {{ id: number, email: string, level: number, organisasiId: number|null }}
   * @throws {Error} jika token tidak valid / expired
   */
  verifyToken(token) {
    try {
      return jwt.verify(token, JWT_SECRET);
    } catch {
      const err = new Error('Token tidak valid atau sudah expired. Silakan login ulang.');
      err.statusCode = 401;
      throw err;
    }
  }
}

module.exports = new AuthService();
