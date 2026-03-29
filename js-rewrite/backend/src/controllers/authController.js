const AuthService = require('../services/AuthService');
const UserRepository = require('../repositories/UserRepository');

/**
 * AuthController (Presentation/API Layer)
 * Menerima request HTTP, meneruskan ke AuthService, dan mengembalikan response.
 * Setara dengan Auth.php Controller di CI4.
 */
class AuthController {
  /**
   * POST /api/auth/login
   * Seperti Auth::login() di CI4 tapi tanpa session, menggunakan JWT.
   */
  async login(req, res, next) {
    try {
      const { email, password } = req.body;

      // Validasi input
      if (!email || !password) {
        return res.status(400).json({
          success: false,
          message: 'Email dan password harus diisi.',
        });
      }

      const result = await AuthService.login(email, password);

      return res.status(200).json({
        success: true,
        message: `Selamat datang, ${result.user.name}!`,
        token: result.token,
        user: result.user,
      });
    } catch (error) {
      // Error dari AuthService (email tidak ditemukan / password salah)
      if (error.statusCode) {
        return res.status(error.statusCode).json({
          success: false,
          message: error.message,
        });
      }
      next(error);
    }
  }

  /**
   * POST /api/auth/register
   * Daftarkan user baru.
   */
  async register(req, res, next) {
    try {
      const { name, email, password, level } = req.body;

      if (!name || !email || !password) {
        return res.status(400).json({
          success: false,
          message: 'Nama, email, dan password harus diisi.',
        });
      }

      if (password.length < 8) {
        return res.status(400).json({
          success: false,
          message: 'Password minimal 8 karakter.',
        });
      }

      const newUser = await AuthService.register({ name, email, password, level });

      return res.status(201).json({
        success: true,
        message: 'Pendaftaran berhasil. Silakan login.',
        data: newUser,
      });
    } catch (error) {
      if (error.statusCode) {
        return res.status(error.statusCode).json({
          success: false,
          message: error.message,
        });
      }
      next(error);
    }
  }

  /**
   * GET /api/auth/me
   * Mengembalikan data user yang sedang login (dari JWT token).
   * Membutuhkan middleware authMiddleware.
   */
  async me(req, res, next) {
    try {
      const user = await UserRepository.findById(req.user.id);
      if (!user) {
        return res.status(404).json({
          success: false,
          message: 'User tidak ditemukan.',
        });
      }
      return res.status(200).json({ success: true, user });
    } catch (error) {
      next(error);
    }
  }
}

module.exports = new AuthController();
