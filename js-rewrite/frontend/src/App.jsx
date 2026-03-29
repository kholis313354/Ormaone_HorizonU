import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import PublicLayout from './layouts/PublicLayout';
import AdminLayout from './layouts/AdminLayout';
import HomePage from './pages/public/HomePage';
import BeritaPage from './pages/public/BeritaPage';
import StrukturPage from './pages/public/StrukturPage';
import SertifikatPage from './pages/public/SertifikatPage';
import VotingPage from './pages/public/VotingPage';
import VotingDetailPage from './pages/public/VotingDetailPage';
import BlockedPage from './pages/public/BlockedPage';
import NotFoundPage from './pages/error/NotFoundPage';
import LoginPage from './pages/auth/LoginPage';
import DashboardPage from './pages/admin/DashboardPage';
import AuthLayout from './layouts/AuthLayout';
import UserManagement from './pages/admin/UserManagement';
import OrganisasiManagement from './pages/admin/OrganisasiManagement';
import FakultasManagement from './pages/admin/FakultasManagement';
import ProfilePage from './pages/admin/ProfilePage';
import InboxPage from './pages/admin/InboxPage';
import AdminBeritaPage from './pages/admin/AdminBeritaPage';
import AdminVotingPage from './pages/admin/AdminVotingPage';
import AdminStrukturPage from './pages/admin/AdminStrukturPage';
import AdminSertifikatPage from './pages/admin/AdminSertifikatPage';

// Simple middleware untuk mengecek auth status di frontend
const ProtectedRoute = ({ children }) => {
  const token = localStorage.getItem('token');
  const userStr = localStorage.getItem('user');
  
  if (!token || !userStr) return <Navigate to="/login" replace />;
  
  try {
    const user = JSON.parse(userStr);
    // Level 1 = Admin, Level 2 = Superadmin
    if (user.level !== 1 && user.level !== 2) {
      return <Navigate to="/blocked" replace />;
    }
  } catch (e) {
    return <Navigate to="/login" replace />;
  }
  
  return children;
};

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Auth Route */}
        <Route element={<AuthLayout />}>
          <Route path="/login" element={<LoginPage />} />
        </Route>

        {/* Public Routes with Navbar & Footer */}
        <Route element={<PublicLayout />}>
          <Route path="/" element={<HomePage />} />
          <Route path="/berita" element={<BeritaPage />} />
          <Route path="/struktur" element={<StrukturPage />} />
          <Route path="/sertifikat" element={<SertifikatPage />} />
          <Route path="/voting" element={<VotingPage />} />
          <Route path="/voting/detail/:id" element={<VotingDetailPage />} />
        </Route>

        {/* Full Screen Public Routes (No Layout or custom) */}
        <Route path="/blocked" element={<BlockedPage />} />
        <Route path="*" element={<NotFoundPage />} />

        {/* Protected Admin Routes with Sidebar */}
        <Route path="/admin" element={
          <ProtectedRoute>
            <AdminLayout />
          </ProtectedRoute>
        }>
          <Route path="dashboard" element={<DashboardPage />} />
          <Route path="profile" element={<ProfilePage />} />
          <Route path="pesan" element={<InboxPage />} />
          <Route path="users" element={<UserManagement />} />
          <Route path="organisasi" element={<OrganisasiManagement />} />
          <Route path="fakultas" element={<FakultasManagement />} />
          <Route path="berita" element={<AdminBeritaPage />} />
          <Route path="evoting" element={<AdminVotingPage />} />
          <Route path="struktur" element={<AdminStrukturPage />} />
          <Route path="sertifikat" element={<AdminSertifikatPage />} />
          <Route index element={<Navigate to="dashboard" replace />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
