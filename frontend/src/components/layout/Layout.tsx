import { useState } from 'react'
import { useAuthStore } from '@/stores/authStore'
import { useThemeStore } from '@/stores/themeStore'
import Sidebar from './Sidebar'
import MobileNav from './MobileNav'
import TopBar from './TopBar'

interface LayoutProps {
  children: React.ReactNode
}

function Layout({ children }: LayoutProps) {
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const { user } = useAuthStore()
  const { theme, toggleTheme } = useThemeStore()
  const isAdmin = user?.role_level >= 60

  return (
    <div className="min-h-screen bg-background">
      {/* Desktop Sidebar */}
      <div className="hidden lg:block">
        <Sidebar isAdmin={isAdmin} />
      </div>

      {/* Mobile Sidebar Overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 z-40 bg-black/50 lg:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Mobile Sidebar */}
      <div
        className={`fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out lg:hidden ${
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <Sidebar isAdmin={isAdmin} onClose={() => setSidebarOpen(false)} />
      </div>

      {/* Main Content */}
      <div className="lg:ml-72">
        <TopBar
          onMenuClick={() => setSidebarOpen(true)}
          theme={theme}
          onThemeToggle={toggleTheme}
        />
        <main className="p-4 md:p-6 pb-24 lg:pb-6 min-h-[calc(100vh-4rem)]">
          <div className="animate-fade-in">
            {children}
          </div>
        </main>
      </div>

      {/* Mobile Bottom Nav */}
      <MobileNav isAdmin={isAdmin} />
    </div>
  )
}

export default Layout
