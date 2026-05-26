import React from 'react'
import { Routes, Route, Navigate } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { useAuthStore } from './stores/authStore'
import { useThemeStore } from './stores/themeStore'
import Layout from './components/layout/Layout'
import LoginPage from './pages/auth/LoginPage'
import AdminDashboard from './pages/admin/AdminDashboard'
import EmployeeDashboard from './pages/employee/EmployeeDashboard'
import EmployeesPage from './pages/admin/EmployeesPage'
import EmployeeDetailPage from './pages/admin/EmployeeDetailPage'
import TasksPage from './pages/admin/TasksPage'
import AttendancePage from './pages/admin/AttendancePage'
import RolesPage from './pages/admin/RolesPage'
import SettingsPage from './pages/admin/SettingsPage'
import MyTasksPage from './pages/employee/MyTasksPage'
import MyAttendancePage from './pages/employee/MyAttendancePage'
import MyKardexPage from './pages/employee/MyKardexPage'

function App() {
  const { user, isAuthenticated } = useAuthStore()
  const { theme } = useThemeStore()

  // Apply theme
  React.useEffect(() => {
    document.documentElement.classList.remove('light', 'dark')
    document.documentElement.classList.add(theme)
  }, [theme])

  if (!isAuthenticated) {
    return (
      <>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route path="*" element={<Navigate to="/login" replace />} />
        </Routes>
        <Toaster position="top-right" />
      </>
    )
  }

  const isAdmin = (user?.role_level ?? 0) >= 60

  return (
    <>
      <Layout>
        <Routes>
          {isAdmin ? (
            <>
              <Route path="/" element={<AdminDashboard />} />
              <Route path="/employees" element={<EmployeesPage />} />
              <Route path="/employees/:id" element={<EmployeeDetailPage />} />
              <Route path="/tasks" element={<TasksPage />} />
              <Route path="/attendance" element={<AttendancePage />} />
              <Route path="/roles" element={<RolesPage />} />
              <Route path="/settings" element={<SettingsPage />} />
              <Route path="*" element={<Navigate to="/" replace />} />
            </>
          ) : (
            <>
              <Route path="/" element={<EmployeeDashboard />} />
              <Route path="/my-tasks" element={<MyTasksPage />} />
              <Route path="/my-attendance" element={<MyAttendancePage />} />
              <Route path="/my-kardex" element={<MyKardexPage />} />
              <Route path="*" element={<Navigate to="/" replace />} />
            </>
          )}
        </Routes>
      </Layout>
      <Toaster position="top-right" />
    </>
  )
}

export default App
