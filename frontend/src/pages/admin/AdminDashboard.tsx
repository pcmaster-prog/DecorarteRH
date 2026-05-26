import { useQuery } from '@tanstack/react-query'
import {
  Users, AlertTriangle, ClipboardList, Clock, TrendingUp,
  TrendingDown, Activity, Cake
} from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function AdminDashboard() {
  const { data, isLoading } = useQuery({
    queryKey: ['dashboard-admin'],
    queryFn: async () => {
      const res = await api.get('/dashboard/admin')
      return res.data
    },
  })

  if (isLoading) {
    return (
      <div className="space-y-6">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {[...Array(8)].map((_, i) => (
            <Skeleton key={i} className="h-24 rounded-xl" />
          ))}
        </div>
        <Skeleton className="h-64 rounded-xl" />
      </div>
    )
  }

  const kpis = data?.kpis || {}
  const liveOperation = data?.live_operation || []
  const alerts = data?.alerts || []

  const kpiCards = [
    { title: 'Empleados Activos', value: kpis.employees_active || 0, icon: Users, color: 'text-blue-600', bg: 'bg-blue-50 dark:bg-blue-900/20' },
    { title: 'Cuentas Suspendidas', value: kpis.employees_suspended || 0, icon: AlertTriangle, color: 'text-red-600', bg: 'bg-red-50 dark:bg-red-900/20' },
    { title: 'Tareas en Curso', value: kpis.tasks_in_progress || 0, icon: ClipboardList, color: 'text-amber-600', bg: 'bg-amber-50 dark:bg-amber-900/20' },
    { title: 'Tareas Hoy', value: kpis.tasks_completed_today || 0, icon: Activity, color: 'text-green-600', bg: 'bg-green-50 dark:bg-green-900/20' },
    { title: 'Retardos Semana', value: kpis.delays_this_week || 0, icon: Clock, color: 'text-orange-600', bg: 'bg-orange-50 dark:bg-orange-900/20' },
    { title: 'Faltas Semana', value: kpis.absences_this_week || 0, icon: TrendingDown, color: 'text-red-600', bg: 'bg-red-50 dark:bg-red-900/20' },
    { title: 'Vacaciones Pend.', value: kpis.vacation_requests_pending || 0, icon: TrendingUp, color: 'text-purple-600', bg: 'bg-purple-50 dark:bg-purple-900/20' },
    { title: 'Asistencia Hoy', value: kpis.attendance_today || 0, icon: Users, color: 'text-teal-600', bg: 'bg-teal-50 dark:bg-teal-900/20' },
  ]

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold">Dashboard Administrativo</h1>
        <p className="text-muted-foreground">Panel de control de operaciones y recursos humanos</p>
      </div>

      {/* KPIs */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        {kpiCards.map((kpi) => {
          const Icon = kpi.icon
          return (
            <Card key={kpi.title} className="hover:shadow-md transition-shadow">
              <CardContent className="p-4">
                <div className="flex items-center justify-between">
                  <div className={`p-2 rounded-lg ${kpi.bg}`}>
                    <Icon className={`h-4 w-4 ${kpi.color}`} />
                  </div>
                  <span className="text-2xl font-bold">{kpi.value}</span>
                </div>
                <p className="text-xs text-muted-foreground mt-2">{kpi.title}</p>
              </CardContent>
            </Card>
          )
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Operación en Vivo */}
        <Card className="lg:col-span-2">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Activity className="h-5 w-5 text-primary" />
              Operación en Vivo
            </CardTitle>
          </CardHeader>
          <CardContent>
            {liveOperation.length === 0 ? (
              <p className="text-muted-foreground text-center py-8">No hay empleados en jornada actualmente</p>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {liveOperation.map((emp: any) => (
                  <div
                    key={emp.employee_id}
                    className="flex items-center gap-3 p-3 rounded-lg bg-secondary/50"
                  >
                    <div
                      className="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0"
                      style={{ backgroundColor: emp.department_color || '#8b5e4c' }}
                    >
                      {emp.name?.charAt(0) || '?'}
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="text-sm font-medium truncate">{emp.name}</p>
                      <p className="text-xs text-muted-foreground">{emp.position}</p>
                      {emp.current_task && (
                        <p className="text-xs text-primary truncate">{emp.current_task}</p>
                      )}
                    </div>
                    <Badge
                      variant={emp.status === 'meal' ? 'warning' : emp.status === 'working' ? 'default' : 'secondary'}
                      className="shrink-0"
                    >
                      {emp.status === 'meal' ? 'Comida' : emp.status === 'working' ? 'Trabajando' : 'Disponible'}
                    </Badge>
                  </div>
                ))}
              </div>
            )}
          </CardContent>
        </Card>

        {/* Alertas */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <AlertTriangle className="h-5 w-5 text-destructive" />
              Alertas
            </CardTitle>
          </CardHeader>
          <CardContent>
            {alerts.length === 0 ? (
              <p className="text-muted-foreground text-center py-8">Sin alertas activas</p>
            ) : (
              <div className="space-y-3">
                {alerts.map((alert: any, i: number) => (
                  <div
                    key={i}
                    className={`p-3 rounded-lg border-l-4 ${
                      alert.severity === 'critical'
                        ? 'border-red-500 bg-red-50 dark:bg-red-900/10'
                        : 'border-amber-500 bg-amber-50 dark:bg-amber-900/10'
                    }`}
                  >
                    <p className="text-sm font-medium">{alert.message}</p>
                    <p className="text-xs text-muted-foreground capitalize mt-1">
                      {alert.type}
                    </p>
                  </div>
                ))}
              </div>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  )
}

export default AdminDashboard
