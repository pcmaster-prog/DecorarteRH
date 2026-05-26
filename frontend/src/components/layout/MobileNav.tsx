import { NavLink, useLocation } from 'react-router-dom'
import {
  LayoutDashboard, Users, ClipboardList, Clock, Settings, Shield, FileText
} from 'lucide-react'
import { cn } from '@/lib/utils'

interface MobileNavProps {
  isAdmin: boolean
}

function MobileNav({ isAdmin }: MobileNavProps) {
  const location = useLocation()

  const adminLinks = [
    { to: '/', icon: LayoutDashboard, label: 'Inicio' },
    { to: '/employees', icon: Users, label: 'Equipo' },
    { to: '/tasks', icon: ClipboardList, label: 'Tareas' },
    { to: '/attendance', icon: Clock, label: 'Asistencia' },
    { to: '/settings', icon: Settings, label: 'Más' },
  ]

  const employeeLinks = [
    { to: '/', icon: LayoutDashboard, label: 'Inicio' },
    { to: '/my-tasks', icon: ClipboardList, label: 'Tareas' },
    { to: '/my-attendance', icon: Clock, label: 'Asistencia' },
    { to: '/my-kardex', icon: FileText, label: 'Kardex' },
  ]

  const links = isAdmin ? adminLinks : employeeLinks

  return (
    <nav className="fixed bottom-0 left-0 right-0 z-50 border-t bg-card lg:hidden">
      <div className="flex items-center justify-around py-2">
        {links.map((link) => {
          const Icon = link.icon
          const isActive = location.pathname === link.to ||
            (link.to !== '/' && location.pathname.startsWith(link.to))

          return (
            <NavLink
              key={link.to}
              to={link.to}
              className={cn(
                "flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-colors",
                isActive
                  ? "text-primary"
                  : "text-muted-foreground"
              )}
            >
              <Icon className="h-5 w-5" />
              <span className="text-[10px] font-medium">{link.label}</span>
            </NavLink>
          )
        })}
      </div>
    </nav>
  )
}

export default MobileNav
