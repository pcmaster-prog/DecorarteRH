import { useQuery } from '@tanstack/react-query'
import {
  Clock, ClipboardList, Calendar, Award, UserCircle, Cake,
  Play, Square, Coffee, ArrowRight
} from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { useAuthStore } from '@/stores/authStore'
import { api } from '@/services/api'
import toast from 'react-hot-toast'
import { useState } from 'react'

function EmployeeDashboard() {
  const { user } = useAuthStore()
  const [isLoadingAction, setIsLoadingAction] = useState(false)

  // Obtener employee_id del usuario
  const employeeId = user?.person?.id

  const { data, isLoading } = useQuery({
    queryKey: ['dashboard-employee', employeeId],
    queryFn: async () => {
      if (!employeeId) return null
      const res = await api.get('/dashboard/employee', { params: { employee_id: employeeId } })
      return res.data
    },
    enabled: !!employeeId,
  })

  const { data: attendanceData } = useQuery({
    queryKey: ['attendance-today', employeeId],
    queryFn: async () => {
      if (!employeeId) return null
      const res = await api.get('/attendance/today', { params: { employee_id: employeeId } })
      return res.data
    },
    enabled: !!employeeId,
    refetchInterval: 30000,
  })

  const handleAttendanceAction = async (action: string) => {
    setIsLoadingAction(true)
    try {
      await api.post(`/attendance/${action}`, { employee_id: employeeId })
      toast.success(action === 'entry' ? 'Entrada registrada' : action === 'exit' ? 'Salida registrada' : action === 'meal/start' ? 'Comida iniciada' : 'Comida finalizada')
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Error')
    } finally {
      setIsLoadingAction(false)
    }
  }

  if (isLoading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-32 rounded-xl" />
        <div className="grid grid-cols-2 gap-4">
          <Skeleton className="h-24 rounded-xl" />
          <Skeleton className="h-24 rounded-xl" />
        </div>
      </div>
    )
  }

  const attendance = attendanceData
  const employee = data?.employee
  const tasks = data?.tasks_today
  const vacation = data?.vacation

  return (
    <div className="space-y-4">
      {/* Welcome */}
      <div className="flex items-center gap-4">
        <div className="h-14 w-14 rounded-full bg-decorarte-100 flex items-center justify-center text-decorarte-700 font-bold text-xl">
          {employee?.name?.charAt(0) || user?.name?.charAt(0) || '?'}
        </div>
        <div>
          <h1 className="text-xl font-bold">¡Hola, {employee?.name?.split(' ')[0] || user?.name?.split(' ')[0]}!</h1>
          <p className="text-sm text-muted-foreground">{employee?.position} · {employee?.department}</p>
        </div>
      </div>

      {/* Attendance Quick Actions */}
      <Card className="border-primary/20">
        <CardContent className="p-4">
          <div className="flex items-center justify-between mb-4">
            <div className="flex items-center gap-2">
              <Clock className="h-5 w-5 text-primary" />
              <span className="font-semibold">Mi Jornada</span>
            </div>
            {attendance?.status && (
              <Badge variant={attendance.status === 'complete' ? 'success' : attendance.status === 'in_progress' ? 'default' : 'secondary'}>
                {attendance.status_label}
              </Badge>
            )}
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
            {!attendance?.entry_time ? (
              <Button
                onClick={() => handleAttendanceAction('entry')}
                isLoading={isLoadingAction}
                className="h-14 text-sm"
              >
                <Play className="h-4 w-4 mr-1" />
                Entrada
              </Button>
            ) : (
              <div className="h-14 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 text-sm font-medium">
                <Clock className="h-4 w-4 mr-1" />
                {attendance.entry_time}
              </div>
            )}

            {attendance?.entry_time && !attendance?.meal_active && !attendance?.exit_time && (
              <Button
                variant="outline"
                onClick={() => handleAttendanceAction('meal/start')}
                isLoading={isLoadingAction}
                className="h-14 text-sm"
              >
                <Coffee className="h-4 w-4 mr-1" />
                Comida
              </Button>
            )}

            {attendance?.meal_active && (
              <Button
                variant="outline"
                onClick={() => handleAttendanceAction('meal/end')}
                isLoading={isLoadingAction}
                className="h-14 text-sm border-amber-500 text-amber-600"
              >
                <Coffee className="h-4 w-4 mr-1" />
                Fin Comida
              </Button>
            )}

            {attendance?.entry_time && !attendance?.exit_time && (
              <Button
                variant="secondary"
                onClick={() => handleAttendanceAction('exit')}
                isLoading={isLoadingAction}
                className="h-14 text-sm"
              >
                <Square className="h-4 w-4 mr-1" />
                Salida
              </Button>
            )}

            {attendance?.exit_time && (
              <div className="h-14 flex items-center justify-center rounded-lg bg-secondary text-sm font-medium">
                <Clock className="h-4 w-4 mr-1" />
                {attendance.exit_time}
              </div>
            )}
          </div>

          {attendance?.is_delay && (
            <p className="text-xs text-amber-600 mt-2 flex items-center gap-1">
              <AlertTriangle className="h-3 w-3" />
              Retardo de {attendance.delay_minutes} minutos registrado
            </p>
          )}
        </CardContent>
      </Card>

      {/* Tasks Summary */}
      <Card>
        <CardHeader className="pb-2">
          <CardTitle className="flex items-center gap-2 text-base">
            <ClipboardList className="h-5 w-5 text-primary" />
            Mis Tareas de Hoy
          </CardTitle>
        </CardHeader>
        <CardContent>
          {tasks?.list?.length === 0 ? (
            <p className="text-muted-foreground text-center py-4">Sin tareas asignadas hoy</p>
          ) : (
            <div className="space-y-2">
              {tasks?.list?.slice(0, 3).map((task: any) => (
                <div
                  key={task.id}
                  className="flex items-center justify-between p-3 rounded-lg bg-secondary/50"
                >
                  <div className="flex items-center gap-2">
                    <div className={`h-2 w-2 rounded-full ${
                      task.status === 'completed' ? 'bg-green-500' :
                      task.status === 'in_progress' ? 'bg-blue-500 animate-pulse' :
                      'bg-gray-400'
                    }`} />
                    <span className="text-sm">{task.title}</span>
                  </div>
                  <Badge variant={task.status === 'completed' ? 'success' : task.status === 'in_progress' ? 'default' : 'secondary'} className="text-xs">
                    {task.status === 'completed' ? 'Completada' : task.status === 'in_progress' ? 'En progreso' : 'Pendiente'}
                  </Badge>
                </div>
              ))}
              {tasks?.list?.length > 3 && (
                <p className="text-xs text-muted-foreground text-center">
                  +{tasks.list.length - 3} tareas más
                </p>
              )}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Quick Stats */}
      <div className="grid grid-cols-2 gap-3">
        <Card>
          <CardContent className="p-4">
            <div className="flex items-center gap-2 mb-2">
              <Calendar className="h-4 w-4 text-primary" />
              <span className="text-xs text-muted-foreground">Vacaciones</span>
            </div>
            <p className="text-2xl font-bold">{vacation?.days_available || 0}</p>
            <p className="text-xs text-muted-foreground">días disponibles</p>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-4">
            <div className="flex items-center gap-2 mb-2">
              <Award className="h-4 w-4 text-primary" />
              <span className="text-xs text-muted-foreground">Antigüedad</span>
            </div>
            <p className="text-lg font-bold truncate">{data?.seniority || 'N/A'}</p>
            <p className="text-xs text-muted-foreground">reconocida</p>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}

export default EmployeeDashboard
