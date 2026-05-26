import { useQuery } from '@tanstack/react-query'
import { ClipboardList, Play, CheckCircle, AlertTriangle } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/Card'
import { Button } from '@/components/ui/Button'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { useAuthStore } from '@/stores/authStore'
import { api } from '@/services/api'
import toast from 'react-hot-toast'
import { useState } from 'react'

function MyTasksPage() {
  const { user } = useAuthStore()
  const employeeId = user?.person?.id
  const [loadingTask, setLoadingTask] = useState<number | null>(null)

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['my-tasks', employeeId],
    queryFn: async () => {
      if (!employeeId) return null
      const res = await api.get('/tasks/my', { params: { employee_id: employeeId } })
      return res.data
    },
    enabled: !!employeeId,
  })

  const handleStart = async (assignmentId: number) => {
    setLoadingTask(assignmentId)
    try {
      await api.post('/tasks/start', { assignment_id: assignmentId })
      toast.success('Tarea iniciada')
      refetch()
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Error')
    } finally {
      setLoadingTask(null)
    }
  }

  const handleComplete = async (assignmentId: number) => {
    setLoadingTask(assignmentId)
    try {
      await api.post('/tasks/complete', { assignment_id: assignmentId })
      toast.success('Tarea completada')
      refetch()
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Error')
    } finally {
      setLoadingTask(null)
    }
  }

  const tasks = data?.data || []
  const summary = data?.summary || {}

  if (isLoading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-24 rounded-xl" />
        <div className="space-y-3">
          {[...Array(3)].map((_, i) => (
            <Skeleton key={i} className="h-32 rounded-xl" />
          ))}
        </div>
      </div>
    )
  }

  return (
    <div className="space-y-4">
      <div>
        <h1 className="text-2xl font-bold">Mis Tareas</h1>
        <p className="text-sm text-muted-foreground">Tareas asignadas para hoy</p>
      </div>

      {/* Summary */}
      <div className="grid grid-cols-4 gap-2">
        <Card>
          <CardContent className="p-3 text-center">
            <p className="text-xl font-bold">{summary.total || 0}</p>
            <p className="text-xs text-muted-foreground">Total</p>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-3 text-center">
            <p className="text-xl font-bold text-amber-600">{summary.pending || 0}</p>
            <p className="text-xs text-muted-foreground">Pendientes</p>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-3 text-center">
            <p className="text-xl font-bold text-blue-600">{summary.in_progress || 0}</p>
            <p className="text-xs text-muted-foreground">En progreso</p>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-3 text-center">
            <p className="text-xl font-bold text-green-600">{summary.completed || 0}</p>
            <p className="text-xs text-muted-foreground">Completadas</p>
          </CardContent>
        </Card>
      </div>

      {/* Tasks List */}
      {tasks.length === 0 ? (
        <Card>
          <CardContent className="p-8 text-center">
            <ClipboardList className="h-12 w-12 text-muted-foreground mx-auto mb-3" />
            <p className="text-muted-foreground">Sin tareas asignadas para hoy</p>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-3">
          {tasks.map((task: any) => (
            <Card key={task.id} className={task.is_overdue ? 'border-red-200' : ''}>
              <CardContent className="p-4">
                <div className="flex items-start justify-between gap-3">
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2 flex-wrap">
                      <h3 className="font-medium">{task.title}</h3>
                      {task.is_overdue && (
                        <Badge variant="destructive" className="text-xs">Vencida</Badge>
                      )}
                    </div>
                    <p className="text-sm text-muted-foreground mt-1">{task.description}</p>
                    <div className="flex items-center gap-3 mt-2 text-xs text-muted-foreground">
                      <span className={`capitalize font-medium ${
                        task.priority === 'critical' ? 'text-red-600' :
                        task.priority === 'high' ? 'text-orange-600' :
                        task.priority === 'medium' ? 'text-yellow-600' :
                        'text-blue-600'
                      }`}>
                        {task.priority}
                      </span>
                      {task.due_time && <span>· Hora límite: {task.due_time}</span>}
                      {task.requires_evidence && <span>· Requiere evidencia</span>}
                    </div>
                  </div>
                  <div className="shrink-0">
                    {task.status === 'pending' && (
                      <Button
                        size="sm"
                        onClick={() => handleStart(task.id)}
                        isLoading={loadingTask === task.id}
                      >
                        <Play className="h-3.5 w-3.5 mr-1" />
                        Iniciar
                      </Button>
                    )}
                    {task.status === 'in_progress' && (
                      <Button
                        size="sm"
                        variant="secondary"
                        onClick={() => handleComplete(task.id)}
                        isLoading={loadingTask === task.id}
                      >
                        <CheckCircle className="h-3.5 w-3.5 mr-1" />
                        Completar
                      </Button>
                    )}
                    {task.status === 'completed' && (
                      <Badge variant="success" className="text-xs">Completada</Badge>
                    )}
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

export default MyTasksPage
