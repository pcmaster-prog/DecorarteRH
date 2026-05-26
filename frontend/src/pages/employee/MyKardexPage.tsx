import { useQuery } from '@tanstack/react-query'
import { FileText, Award, Calendar, Shield, Clock } from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { useAuthStore } from '@/stores/authStore'
import { api } from '@/services/api'

function MyKardexPage() {
  const { user } = useAuthStore()
  const employeeId = user?.person?.id

  const { data, isLoading } = useQuery({
    queryKey: ['employee-kardex', employeeId],
    queryFn: async () => {
      if (!employeeId) return null
      const res = await api.get(`/employees/${employeeId}/kardex`)
      return res.data
    },
    enabled: !!employeeId,
  })

  if (isLoading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-32 rounded-xl" />
        <Skeleton className="h-48 rounded-xl" />
      </div>
    )
  }

  const employee = data?.data?.employee
  const seniority = data?.data?.seniority
  const vacationBalances = data?.data?.vacation_balances || []
  const historicalBenefits = data?.data?.historical_benefits || []

  return (
    <div className="space-y-4">
      <div>
        <h1 className="text-2xl font-bold">Mi Kardex</h1>
        <p className="text-sm text-muted-foreground">Mi expediente laboral</p>
      </div>

      {/* Profile */}
      <Card>
        <CardContent className="p-4">
          <div className="flex items-center gap-4">
            <div className="h-16 w-16 rounded-full bg-decorarte-100 flex items-center justify-center text-decorarte-700 font-bold text-xl">
              {employee?.full_name?.charAt(0) || '?'}
            </div>
            <div>
              <h2 className="text-lg font-bold">{employee?.full_name}</h2>
              <p className="text-sm text-muted-foreground">{employee?.position} · {employee?.department}</p>
              <p className="text-sm text-muted-foreground">{employee?.employee_number}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Seniority */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Award className="h-5 w-5 text-primary" />
            Mi Antigüedad
          </CardTitle>
        </CardHeader>
        <CardContent>
          {seniority ? (
            <div className="space-y-3">
              <div className="p-4 rounded-lg bg-primary/5 border border-primary/20 text-center">
                <p className="text-2xl font-bold text-primary">{seniority.recognized_label}</p>
                <p className="text-sm text-muted-foreground mt-1">Antigüedad reconocida</p>
              </div>
              <p className="text-sm text-muted-foreground text-center">
                {seniority.status === 'validated'
                  ? '✓ Antigüedad validada por administración'
                  : '⏳ Antigüedad pendiente de validación'}
              </p>
            </div>
          ) : (
            <p className="text-muted-foreground text-center py-4">Sin registro de antigüedad</p>
          )}
        </CardContent>
      </Card>

      {/* Vacations */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Calendar className="h-5 w-5 text-primary" />
            Mis Vacaciones
          </CardTitle>
        </CardHeader>
        <CardContent>
          {vacationBalances.length === 0 ? (
            <p className="text-muted-foreground text-center py-4">Sin registros de vacaciones</p>
          ) : (
            <div className="space-y-3">
              {vacationBalances.map((balance: any) => (
                <div key={balance.period_year} className="p-3 rounded-lg bg-secondary/50">
                  <div className="flex justify-between items-center">
                    <span className="font-medium">Periodo {balance.period_year}</span>
                    <Badge variant="success" className="text-xs">{balance.days_available} días disp.</Badge>
                  </div>
                  <div className="grid grid-cols-3 gap-2 mt-2 text-center text-sm">
                    <div>
                      <p className="font-bold">{balance.days_generated}</p>
                      <p className="text-xs text-muted-foreground">Generados</p>
                    </div>
                    <div>
                      <p className="font-bold">{balance.days_taken}</p>
                      <p className="text-xs text-muted-foreground">Tomados</p>
                    </div>
                    <div>
                      <p className="font-bold text-green-600">{balance.days_available}</p>
                      <p className="text-xs text-muted-foreground">Disponibles</p>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Benefits */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Shield className="h-5 w-5 text-primary" />
            Mis Prestaciones
          </CardTitle>
        </CardHeader>
        <CardContent>
          {historicalBenefits.length === 0 ? (
            <p className="text-muted-foreground text-center py-4">Sin registros de prestaciones</p>
          ) : (
            <div className="space-y-2">
              {historicalBenefits.filter((b: any) => b.published_to_employee).map((benefit: any, i: number) => (
                <div key={i} className="flex items-center justify-between p-3 rounded-lg bg-secondary/50">
                  <div>
                    <p className="font-medium text-sm capitalize">{benefit.benefit_type.replace('_', ' ')}</p>
                    <p className="text-xs text-muted-foreground">Año {benefit.period_year}</p>
                  </div>
                  <div className="text-right">
                    <Badge variant={benefit.status_color} className="text-xs">{benefit.status_label}</Badge>
                    {benefit.amount_paid && (
                      <p className="text-sm font-medium">${benefit.amount_paid.toLocaleString()}</p>
                    )}
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Attendance Summary */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Clock className="h-5 w-5 text-primary" />
            Resumen de Asistencia
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-2">
          <div className="flex justify-between text-sm">
            <span className="text-muted-foreground">Total retardos acumulados</span>
            <span className="font-medium">{data?.data?.attendance_summary?.total_delays || 0}</span>
          </div>
          <div className="flex justify-between text-sm">
            <span className="text-muted-foreground">Total faltas</span>
            <span className="font-medium">{data?.data?.attendance_summary?.total_absences || 0}</span>
          </div>
          <p className="text-xs text-muted-foreground mt-2">
            3 retardos equivalen a 1 falta. 3 faltas generan suspensión preventiva.
          </p>
        </CardContent>
      </Card>
    </div>
  )
}

export default MyKardexPage
