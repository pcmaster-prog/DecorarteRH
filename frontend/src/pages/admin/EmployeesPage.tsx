import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { useNavigate } from 'react-router-dom'
import { Search, Users, ChevronRight, Filter } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function EmployeesPage() {
  const [search, setSearch] = useState('')
  const navigate = useNavigate()

  const { data, isLoading } = useQuery({
    queryKey: ['employees', search],
    queryFn: async () => {
      const res = await api.get('/employees', { params: { search, per_page: 50 } })
      return res.data
    },
  })

  const employees = data?.data || []

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">Empleados</h1>
          <p className="text-sm text-muted-foreground">Gestión del equipo de trabajo</p>
        </div>
        <div className="flex items-center gap-2">
          <Users className="h-5 w-5 text-muted-foreground" />
          <span className="text-sm font-medium">{employees.length}</span>
        </div>
      </div>

      {/* Search */}
      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
        <Input
          placeholder="Buscar por nombre, email o puesto..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-10"
        />
      </div>

      {/* Employee List */}
      {isLoading ? (
        <div className="space-y-3">
          {[...Array(5)].map((_, i) => (
            <Skeleton key={i} className="h-20 rounded-xl" />
          ))}
        </div>
      ) : (
        <div className="space-y-3">
          {employees.map((emp: any) => (
            <Card
              key={emp.id}
              className="cursor-pointer hover:shadow-md transition-shadow"
              onClick={() => navigate(`/employees/${emp.id}`)}
            >
              <CardContent className="p-4">
                <div className="flex items-center gap-4">
                  <div
                    className="h-12 w-12 rounded-full flex items-center justify-center text-white font-semibold shrink-0"
                    style={{ backgroundColor: emp.department_color || '#8b5e4c' }}
                  >
                    {emp.full_name?.charAt(0) || '?'}
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2">
                      <p className="font-medium truncate">{emp.full_name}</p>
                      <Badge variant={emp.status_color === 'green' ? 'success' : emp.status_color === 'red' ? 'destructive' : 'secondary'} className="text-xs shrink-0">
                        {emp.status_label}
                      </Badge>
                    </div>
                    <p className="text-sm text-muted-foreground">{emp.position} · {emp.department}</p>
                    <div className="flex items-center gap-3 mt-1 text-xs text-muted-foreground">
                      <span>{emp.employee_number}</span>
                      <span>·</span>
                      <span>{emp.seniority}</span>
                      <span>·</span>
                      <span>Supervisor: {emp.supervisor || 'N/A'}</span>
                    </div>
                  </div>
                  <ChevronRight className="h-5 w-5 text-muted-foreground shrink-0" />
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  )
}

export default EmployeesPage
