import { NavLink, useLocation } from 'react-router-dom'
import {
  LayoutDashboard, Users, ClipboardList, Clock, Settings,
  Shield, Briefcase, UserCircle, Sun, Moon, X, Cake,
  GraduationCap, FileText, AlertTriangle, ChevronRight
} from 'lucide-react'
import { cn } from '@/lib/utils'
import { useAuthStore } from '@/stores/authStore'

interface SidebarProps {
  isAdmin: boolean
  onClose?: () => void
}

function Sidebar({ isAdmin, onClose }: SidebarProps) {
  const { user } = useAuthStore()
  const location = useLocation()

  const adminLinks = [
    { to: '/', icon: LayoutDashboard, label: 'Dashboard' },
    { to: '/employees', icon: Users, label: 'Empleados' },
    { to: '/attendance', icon: Clock, label: 'Asistencia' },
    { to: '/tasks', icon: ClipboardList, label: 'Tareas' },
    { to: '/roles', icon: Shield, label: 'Roles y Permisos' },
    { to: '/settings', icon: Settings, label: 'Configuración' },
  ]

  const employeeLinks = [
    { to: '/', icon: LayoutDashboard, label: 'Mi Panel' },
    { to: '/my-tasks', icon: ClipboardList, label: 'Mis Tareas' },
    { to: '/my-attendance', icon: Clock, label: 'Mi Asistencia' },
    { to: '/my-kardex', icon: FileText, label: 'Mi Kardex' },
  ]

  const links = isAdmin ? adminLinks : employeeLinks

  return (
    <div className="flex h-full flex-col bg-card border-r border-border">
      {/* Header */}
      <div className="flex h-16 items-center justify-between px-4 border-b border-border">
        <div className="flex items-center gap-2">
          <div className="h-8 w-8 rounded-lg bg-decorarte-600 flex items-center justify-center">
            <Cake className="h-5 w-5 text-white" />
          </div>
          <div>
            <h1 className="font-bold text-sm text-foreground">DecorArte</h1>
            <p className="text-[10px] text-muted-foreground -mt-0.5">RH Operativo</p>
          </div>
        </div>
        {onClose && (
          <button onClick={onClose} className="lg:hidden p-1 rounded-md hover:bg-accent">
            <X className="h-5 w-5" />
          </button>
        )}
      </div>

      {/* User Info */}
      <div className="p-4 border-b border-border">
        <div className="flex items-center gap-3">
          <div className="h-10 w-10 rounded-full bg-decorarte-100 flex items-center justify-center text-decorarte-700 font-semibold text-sm">
            {user?.name?.charAt(0) || '?'}
          </div>
          <div className="min-w-0">
            <p className="text-sm font-medium truncate">{user?.name}</p>
            <p className="text-xs text-muted-foreground truncate">{user?.role}</p>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto p-2 space-y-1">
        {links.map((link) => {
          const Icon = link.icon
          const isActive = location.pathname === link.to ||
            (link.to !== '/' && location.pathname.startsWith(link.to))

          return (
            <NavLink
              key={link.to}
              to={link.to}
              onClick={onClose}
              className={cn(
                "flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors",
                isActive
                  ? "bg-primary/10 text-primary"
                  : "text-muted-foreground hover:bg-accent hover:text-foreground"
              )}
            >
              <Icon className="h-4 w-4 shrink-0" />
              <span className="truncate">{link.label}</span>
              {isActive && <ChevronRight className="h-4 w-4 ml-auto shrink-0" />}
            </NavLink>
          )
        })}
      </nav>

      {/* Footer */}
      <div className="border-t border-border p-4">
        <p className="text-xs text-muted-foreground text-center">
          DecorArte RH Operativo v1.0
        </p>
      </div>
    </div>
  )
}

export default Sidebar
