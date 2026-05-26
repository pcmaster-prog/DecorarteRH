import { useParams } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import { ArrowLeft, User, Briefcase, Clock, Award, FileText, Shield } from 'lucide-react'
import { useNavigate } from 'react-router-dom'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { Skeleton } from '@/components/ui/Skeleton'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/Tabs'
import { api } from '@/services/api'

function EmployeeDetailPage() {
  const { id } = useParams()
  const navigate = useNavigate()

  const { data, isLoading } = useQuery({
    queryKey: ['employee-kardex', id],
    queryFn: async () => {
      const res = await api.get(`/employees/${id}/kardex`)
      return res.data
    },
  })

  if (isLoading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-32 rounded-xl" />
        <Skeleton className="h-64 rounded-xl" />
      </div>
    )
  }

  const employee = data?.data?.employee
  const seniority = data?.data?.seniority
  const vacationBalances = data?.data?.vacation_balances || []
  const historicalBenefits = data?.data?.historical_benefits || []
  const attendanceSummary = data?.data?.attendance_summary || {}
  const suspensions = data?.data?.suspensions || []
  const warnings = data?.data?.warnings || []

  return (
    <div className="space-y-4">
      {/* Header */}
      <div className="flex items-center gap-3">
        <Button variant="ghost" size="icon" onClick={() => navigate('/employees')}>
          <ArrowLeft className="h-5 w-5" />
        </Button>
        <div>
          <h1 className="text-xl font-bold">Kardex del Empleado</h1>
          <p className="text-sm text-muted-foreground">Información completa del colaborador</p>
        </div>
      </div>

      {/* Profile Card */}
      <Card>
        <CardContent className="p-4 md:p-6">
          <div className="flex flex-col md:flex-row items-start md:items-center gap-4">
            <div
              className="h-20 w-20 rounded-full flex items-center justify-center text-white text-2xl font-bold shrink-0"
              style={{ backgroundColor: employee?.department_color || '#8b5e4c' }}
            >
              {employee?.full_name?.charAt(0) || '?'}
            </div>
            <div className="flex-1">
              <div className="flex items-center gap-2 flex-wrap">
                <h2 className="text-xl font-bold">{employee?.full_name}</h2>
                <Badge variant={employee?.status_color === 'green' ? 'success' : employee?.status_color === 'red' ? 'destructive' : 'secondary'}>
                  {employee?.status_label}
                </Badge>
              </div>
              <p className="text-muted-foreground">{employee?.position} · {employee?.department}</p>
              <div className="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm">
                <span className="flex items-center gap-1 text-muted-foreground">
                  <Briefcase className="h-3.5 w-3.5" />
                  {employee?.employee_number}
                </span>
                <span className="flex items-center gap-1 text-muted-foreground">
                  <Clock className="h-3.5 w-3.5" />
                  {employee?.seniority}
                </span>
                <span className="flex items-center gap-1 text-muted-foreground">
                  <User className="h-3.5 w-3.5" />
                  Supervisor: {employee?.supervisor || 'N/A'}
                </span>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Tabs */}
      <Tabs defaultValue="general" className="w-full">
        <TabsList className="w-full justify-start overflow-x-auto">
          <TabsTrigger value="general">General</TabsTrigger>
          <TabsTrigger value="seniority">Antigüedad</TabsTrigger>
          <TabsTrigger value="vacations">Vacaciones</TabsTrigger>
          <TabsTrigger value="benefits">Prestaciones</TabsTrigger>
          <TabsTrigger value="attendance">Asistencia</TabsTrigger>
          <TabsTrigger value="incidents">Incidencias</TabsTrigger>
        </TabsList>

        <TabsContent value="general" className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Información Personal</CardTitle>
              </CardHeader>
              <CardContent className="space-y-2 text-sm">
                <div className="flex justify-between">
                  <span className="text-muted-foreground">CURP</span>
                  <span>{employee?.curp || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">RFC</span>
                  <span>{employee?.rfc || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">NSS</span>
                  <span>{employee?.nss || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Fecha de nacimiento</span>
                  <span>{employee?.date_of_birth || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Email</span>
                  <span>{employee?.email || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Teléfono</span>
                  <span>{employee?.phone || 'N/A'}</span>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Información Laboral</CardTitle>
              </CardHeader>
              <CardContent className="space-y-2 text-sm">
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Fecha de ingreso</span>
                  <span>{employee?.hire_date || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Tipo de empleado</span>
                  <span>{employee?.employee_type || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Turno</span>
                  <span>{employee?.shift || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Día de descanso</span>
                  <span className="capitalize">{employee?.rest_day || 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Salario base</span>
                  <span>{employee?.base_salary ? `$${employee.base_salary.toLocaleString()}` : 'N/A'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">Gerente</span>
                  <span>{employee?.manager || 'N/A'}</span>
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        <TabsContent value="seniority" className="space-y-4">
          {seniority ? (
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2 text-base">
                  <Award className="h-5 w-5 text-primary" />
                  Antigüedad Histórica y Prestaciones Acumuladas
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div className="p-4 rounded-lg bg-secondary/50 text-center">
                    <p className="text-2xl font-bold text-primary">{seniority.total_historical_hours}</p>
                    <p className="text-xs text-muted-foreground">Horas históricas</p>
                  </div>
                  <div className="p-4 rounded-lg bg-secondary/50 text-center">
                    <p className="text-2xl font-bold">{seniority.equivalent_years}</p>
                    <p className="text-xs text-muted-foreground">Años equivalentes</p>
                  </div>
                  <div className="p-4 rounded-lg bg-secondary/50 text-center">
                    <p className="text-2xl font-bold">{seniority.equivalent_months}</p>
                    <p className="text-xs text-muted-foreground">Meses equivalentes</p>
                  </div>
                  <div className="p-4 rounded-lg bg-secondary/50 text-center">
                    <p className="text-2xl font-bold">{seniority.equivalent_days}</p>
                    <p className="text-xs text-muted-foreground">Días equivalentes</p>
                  </div>
                </div>

                <div className="p-4 rounded-lg bg-primary/5 border border-primary/20">
                  <p className="text-sm font-medium text-primary">Antigüedad reconocida:</p>
                  <p className="text-lg font-bold mt-1">{seniority.human_readable}</p>
                  <p className="text-sm text-muted-foreground mt-1">Visible para empleado: {seniority.recognized_label}</p>
                </div>

                <div className="grid grid-cols-2 gap-3 text-sm">
                  <div className="flex items-center gap-2">
                    <Shield className="h-4 w-4 text-green-600" />
                    <span>Impacta vacaciones: {seniority.impacts.vacations ? 'Sí' : 'No'}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Shield className="h-4 w-4 text-green-600" />
                    <span>Impacta aguinaldo: {seniority.impacts.christmas_bonus ? 'Sí' : 'No'}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Shield className="h-4 w-4 text-green-600" />
                    <span>Impacta PTU: {seniority.impacts.profit_sharing ? 'Sí' : 'No'}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Shield className="h-4 w-4 text-green-600" />
                    <span>Impacta finiquito: {seniority.impacts.severance ? 'Sí' : 'No'}</span>
                  </div>
                </div>

                <div className="flex items-center gap-2 text-sm">
                  <Badge variant={seniority.status === 'validated' ? 'success' : 'warning'}>
                    {seniority.status === 'validated' ? 'Validada' : seniority.status}
                  </Badge>
                  {seniority.validated_by && (
                    <span className="text-muted-foreground">Validado por: {seniority.validated_by}</span>
                  )}
                </div>
              </CardContent>
            </Card>
          ) : (
            <Card>
              <CardContent className="p-8 text-center">
                <p className="text-muted-foreground">No hay registro de antigüedad histórica</p>
              </CardContent>
            </Card>
          )}
        </TabsContent>

        <TabsContent value="vacations" className="space-y-4">
          {vacationBalances.length === 0 ? (
            <Card>
              <CardContent className="p-8 text-center">
                <p className="text-muted-foreground">Sin registros de vacaciones</p>
              </CardContent>
            </Card>
          ) : (
            vacationBalances.map((balance: any) => (
              <Card key={balance.period_year}>
                <CardHeader>
                  <CardTitle className="text-base">Periodo {balance.period_year}</CardTitle>
                </CardHeader>
                <CardContent className="space-y-2">
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div className="text-center">
                      <p className="text-xl font-bold text-primary">{balance.days_generated}</p>
                      <p className="text-xs text-muted-foreground">Días generados</p>
                    </div>
                    <div className="text-center">
                      <p className="text-xl font-bold">{balance.days_taken}</p>
                      <p className="text-xs text-muted-foreground">Días tomados</p>
                    </div>
                    <div className="text-center">
                      <p className="text-xl font-bold text-green-600">{balance.days_available}</p>
                      <p className="text-xs text-muted-foreground">Días disponibles</p>
                    </div>
                    <div className="text-center">
                      <p className="text-xl font-bold">{balance.days_pending}</p>
                      <p className="text-xs text-muted-foreground">Días pendientes</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))
          )}
        </TabsContent>

        <TabsContent value="benefits" className="space-y-4">
          {historicalBenefits.length === 0 ? (
            <Card>
              <CardContent className="p-8 text-center">
                <p className="text-muted-foreground">Sin registros de prestaciones</p>
              </CardContent>
            </Card>
          ) : (
            <div className="space-y-3">
              {historicalBenefits.map((benefit: any, i: number) => (
                <Card key={i}>
                  <CardContent className="p-4">
                    <div className="flex items-center justify-between">
                      <div>
                        <p className="font-medium capitalize">{benefit.benefit_type.replace('_', ' ')}</p>
                        <p className="text-sm text-muted-foreground">Año {benefit.period_year}</p>
                      </div>
                      <div className="text-right">
                        <Badge variant={benefit.status_color}>
                          {benefit.status_label}
                        </Badge>
                        {benefit.amount_paid && (
                          <p className="text-sm font-medium mt-1">${benefit.amount_paid.toLocaleString()}</p>
                        )}
                      </div>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          )}
        </TabsContent>

        <TabsContent value="attendance" className="space-y-4">
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Resumen de Asistencia</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              <div className="flex justify-between text-sm">
                <span className="text-muted-foreground">Total retardos</span>
                <span className="font-medium">{attendanceSummary.total_delays}</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className="text-muted-foreground">Total faltas</span>
                <span className="font-medium">{attendanceSummary.total_absences}</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className="text-muted-foreground">Faltas convertidas (3 retardos = 1 falta)</span>
                <span className="font-medium text-destructive">{attendanceSummary.converted_absences}</span>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="incidents" className="space-y-4">
          {suspensions.length === 0 && warnings.length === 0 ? (
            <Card>
              <CardContent className="p-8 text-center">
                <p className="text-muted-foreground">Sin incidencias registradas</p>
              </CardContent>
            </Card>
          ) : (
            <>
              {suspensions.map((suspension: any, i: number) => (
                <Card key={`s-${i}`} className="border-red-200 dark:border-red-800">
                  <CardContent className="p-4">
                    <div className="flex items-center gap-2 mb-2">
                      <Shield className="h-4 w-4 text-red-600" />
                      <span className="font-medium text-red-600">Suspensión Preventiva</span>
                    </div>
                    <p className="text-sm">{suspension.reason}</p>
                    <p className="text-xs text-muted-foreground mt-1">
                      {suspension.absence_count} faltas · {new Date(suspension.suspended_at).toLocaleDateString('es-MX')}
                    </p>
                  </CardContent>
                </Card>
              ))}
              {warnings.map((warning: any, i: number) => (
                <Card key={`w-${i}`}>
                  <CardContent className="p-4">
                    <div className="flex items-center gap-2 mb-2">
                      <FileText className="h-4 w-4 text-amber-600" />
                      <span className="font-medium">Llamada de Atención</span>
                      <Badge variant={warning.severity_color} className="text-xs">{warning.severity}</Badge>
                    </div>
                    <p className="text-sm">{warning.reason}</p>
                    <p className="text-xs text-muted-foreground mt-1">
                      {new Date(warning.issued_at).toLocaleDateString('es-MX')}
                      {warning.is_acknowledged && ' · Acusada'}
                    </p>
                  </CardContent>
                </Card>
              ))}
            </>
          )}
        </TabsContent>
      </Tabs>
    </div>
  )
}

export default EmployeeDetailPage
