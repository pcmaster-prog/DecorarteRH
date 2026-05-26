import { useQuery } from '@tanstack/react-query'
import { Clock, Play, Square, Coffee, AlertTriangle, Calendar } from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { useAuthStore } from '@/stores/authStore'
import { api } from '@/services/api'
import toast from 'react-hot-toast'
import { useState } from 'react'

function MyAttendancePage() {
  const { user } = useAuthStore()
  const employeeId = user?.person?.id
  const [loading, setLoading] = useState(false)

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['attendance-today', employeeId],
    queryFn: async () => {
      if (!employeeId) return null
      const res = await api.get('/attendance/today', { params: { employee_id: employeeId } })
      return res.data
    },
    enabled: !!employeeId,
    refetchInterval: 30000,
  })

  const handleAction = async (action: string) => {
    setLoading(true)
    try {
      await api.post(`/attendance/${action}`, { employee_id: employeeId })
      toast.success(
        action === 'entry' ? 'Entrada registrada' :
        action === 'exit' ? 'Salida registrada' :
        action === 'meal/start' ? 'Comida iniciada' : 'Comida finalizada'
      )
      refetch()
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Error')
    } finally {
      setLoading(false)
    }
  }

  if (isLoading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-48 rounded-xl" />
        <Skeleton className="h-64 rounded-xl" />
      </div>
    )
  }

  const attendance = data

  return (
    <div className="space-y-4">
      <div>
        <h1 className="text-2xl font-bold">Mi Asistencia</h1>
        <p className="text-sm text-muted-foreground">Registro de jornada laboral</p>
      </div>

      {/* Main Timer Card */}
      <Card className="border-primary/20">
        <CardContent className="p-6">
          <div className="text-center mb-6">
            <Clock className="h-12 w-12 text-primary mx-auto mb-3" />
            <p className="text-4xl font-bold font-mono">
              {new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })}
            </p>
            <p className="text-sm text-muted-foreground mt-1">
              {new Date().toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
            </p>
          </div>

          <div className="grid grid-cols-2 gap-3">
            {!attendance?.entry_time ? (
              <Button
                className="h-16 text-lg"
                onClick={() => handleAction('entry')}
                isLoading={loading}
              >
                <Play className="h-5 w-5 mr-2" />
                Registrar Entrada
              </Button>
            ) : (
              <div className="h-16 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 font-medium">
                <Clock className="h-5 w-5 mr-2" />
                Entrada: {attendance.entry_time}
              </div>
            )}

            {attendance?.entry_time && !attendance?.exit_time ? (
              <Button
                variant="secondary"
                className="h-16 text-lg"
                onClick={() => handleAction('exit')}
                isLoading={loading}
              >
                <Square className="h-5 w-5 mr-2" />
                Registrar Salida
              </Button>
            ) : attendance?.exit_time ? (
              <div className="h-16 flex items-center justify-center rounded-lg bg-secondary text-foreground font-medium">
                <Clock className="h-5 w-5 mr-2" />
                Salida: {attendance.exit_time}
              </div>
            ) : (
              <div className="h-16 flex items-center justify-center rounded-lg bg-muted text-muted-foreground">
                Esperando entrada
              </div>
            )}
          </div>

          {attendance?.entry_time && !attendance?.exit_time && (
            <div className="grid grid-cols-2 gap-3 mt-3">
              {!attendance?.meal_active ? (
                <Button
                  variant="outline"
                  className="h-14"
                  onClick={() => handleAction('meal/start')}
                  isLoading={loading}
                >
                  <Coffee className="h-4 w-4 mr-2" />
                  Iniciar Comida
                </Button>
              ) : (
                <Button
                  variant="outline"
                  className="h-14 border-amber-500 text-amber-600"
                  onClick={() => handleAction('meal/end')}
                  isLoading={loading}
                >
                  <Coffee className="h-4 w-4 mr-2" />
                  Finalizar Comida
                </Button>
              )}
            </div>
          )}

          {attendance?.is_delay && (
            <div className="mt-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200">
              <div className="flex items-center gap-2 text-amber-700">
                <AlertTriangle className="h-4 w-4" />
                <span className="text-sm font-medium">Retardo registrado</span>
              </div>
              <p className="text-sm text-amber-600 mt-1">
                Entrada con {attendance.delay_minutes} minutos de retardo. 
                Salida sugerida ajustada.
              </p>
            </div>
          )}
        </CardContent>
      </Card>

      {/* Status */}
      <Card>
        <CardHeader>
          <CardTitle className="text-base">Estado de Hoy</CardTitle>
        </CardHeader>
        <CardContent className="space-y-3">
          <div className="flex justify-between text-sm">
            <span className="text-muted-foreground">Estado</span>
            <Badge variant={attendance?.status === 'complete' ? 'success' : attendance?.status === 'in_progress' ? 'default' : 'secondary'}>
              {attendance?.status_label || 'Sin registrar'}
            </Badge>
          </div>
          <div className="flex justify-between text-sm">
            <span className="text-muted-foreground">Horas trabajadas</span>
            <span className="font-medium">{attendance?.worked_hours || 0}h</span>
          </div>
          <div className="flex justify-between text-sm">
            <span className="text-muted-foreground">Horas efectivas</span>
            <span className="font-medium">{attendance?.effective_hours || 0}h</span>
          </div>
        </CardContent>
      </Card>

      {/* Rules */}
      <Card>
        <CardHeader>
          <CardTitle className="text-base flex items-center gap-2">
            <Calendar className="h-4 w-4" />
            Reglas de Asistencia
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-2 text-sm">
          <p className="text-muted-foreground">• Jornada: 8:30 a.m. a 5:00 p.m.</p>
          <p className="text-muted-foreground">• Tolerancia operativa: 10 minutos</p>
          <p className="text-muted-foreground">• Comida: 30 minutos</p>
          <p className="text-muted-foreground">• 3 retardos = 1 falta</p>
          <p className="text-muted-foreground">• 3 faltas = suspensión preventiva</p>
        </CardContent>
      </Card>
    </div>
  )
}

export default MyAttendancePage
