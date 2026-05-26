import { Menu, Bell, LogOut, Sun, Moon } from 'lucide-react'
import { Button } from '@/components/ui/Button'
import { useAuthStore } from '@/stores/authStore'
import toast from 'react-hot-toast'

interface TopBarProps {
  onMenuClick: () => void
  theme: string
  onThemeToggle: () => void
}

function TopBar({ onMenuClick, theme, onThemeToggle }: TopBarProps) {
  const { user, logout } = useAuthStore()

  const handleLogout = () => {
    logout()
    toast.success('Sesión cerrada correctamente')
  }

  return (
    <header className="sticky top-0 z-30 flex h-16 items-center justify-between border-b bg-card/80 px-4 backdrop-blur-md lg:px-6">
      <div className="flex items-center gap-3">
        <Button
          variant="ghost"
          size="icon"
          className="lg:hidden"
          onClick={onMenuClick}
        >
          <Menu className="h-5 w-5" />
        </Button>
        <h2 className="text-lg font-semibold hidden sm:block">
          {user?.role || 'Panel'}
        </h2>
      </div>

      <div className="flex items-center gap-2">
        <Button variant="ghost" size="icon" onClick={onThemeToggle}>
          {theme === 'dark' ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
        </Button>

        <Button variant="ghost" size="icon" className="relative">
          <Bell className="h-4 w-4" />
          <span className="absolute -right-0.5 -top-0.5 h-4 w-4 rounded-full bg-destructive text-[10px] font-medium text-destructive-foreground flex items-center justify-center">
            3
          </span>
        </Button>

        <Button variant="ghost" size="icon" onClick={handleLogout}>
          <LogOut className="h-4 w-4" />
        </Button>
      </div>
    </header>
  )
}

export default TopBar
