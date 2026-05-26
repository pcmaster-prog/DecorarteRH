import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { Clock, Calendar, ChevronLeft, ChevronRight } from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function AttendancePage() {
  const [date, setDate] = useState(new Date().toISOString().split('T')[0])

  const { data, isLoading } = useQuery({
    queryKey: ['attendance', date],
    queryFn: async () => {
      const res = await api.get('/attendance', { params: { date, per_page: 50 } })
      return res.data
    },
  })

  const logs = data?.data || []

  const changeDate = (days: number) => {
    const d = new Date(date)
    d.setDate(d.getDate() + days)
    setDate(d.toISOString().split('T')[0])
  }

  return (
    <div className="space-y-4">
      <div>
        <h1 className="text-2xl font-bold">Asistencia</h1>
        <p className="text-sm text-muted-foreground">Registro de entradas, salidas y retardos</p>
      </div>

      {/* Date Navigator */}
      <Card>
        <CardContent className="p-4">
          <div className="flex items-center justify-between">
            <Button variant="ghost" size="icon" onClick={() => changeDate(-1)}>
              <ChevronLeft className="h-5 w-5" />
            </Button>
            <div className="flex items-center gap-2">
              <Calendar className="h-5 w-5 text-primary" />
              <span className="font-medium">
                {new Date(date).toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
              </span>
            </div>
            <Button variant="ghost" size="icon" onClick={() => changeDate(1)}>
              <ChevronRight className="h-5 w-5" />
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Attendance List */}
      {isLoading ? (
        <div className="space-y-3">
          {[...Array(5)].map((_, i) => (
            <Skeleton key={i} className="h-20 rounded-xl" />
          ))}
        </div>
      ) : logs.length === 0 ? (
        <Card>
          <CardContent className="p-8 text-center">
            <Clock className="h-12 w-12 text-muted-foreground mx-auto mb-3" />
            <p className="text-muted-foreground">Sin registros de asistencia para esta fecha</p>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-3">
          {logs.map((log: any) => (
            <Card key={log.id}>
              <CardContent className="p-4">
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold">
                      {log.employee_name?.charAt(0) || '?'}
                    </div>
                    <div>
                      <p className="font-medium">{log.employee_name}</p>
                      <div className="flex items-center gap-2 text-sm text-muted-foreground">
                        <span>Entrada: {log.entry_time || '—'}</span>
                        <span>·</span>
                        <span>Salida: {log.exit_time || '—'}</span>
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    {log.is_delay && (
                      <Badge variant="warning" className="mb-1">Retardo {log.delay_minutes}min</Badge>
                    )}
                    {log.is_absence && (
                      <Badge variant="destructive" className="mb-1">Falta</Badge>
                    )}
                    <p className="text-xs text-muted-foreground">
                      {log.worked_hours}h trabajadas
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  )
}

export default AttendancePage
