import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { ClipboardList, Plus, Search, Filter } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/Card'
import { Input } from '@/components/ui/Input'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function TasksPage() {
  const [search, setSearch] = useState('')

  const { data, isLoading } = useQuery({
    queryKey: ['tasks', search],
    queryFn: async () => {
      const res = await api.get('/tasks', { params: { search } })
      return res.data
    },
  })

  const tasks = data?.data || []

  const priorityColors: Record<string, string> = {
    critical: 'bg-red-100 text-red-700',
    high: 'bg-orange-100 text-orange-700',
    medium: 'bg-yellow-100 text-yellow-700',
    low: 'bg-blue-100 text-blue-700',
    flexible: 'bg-gray-100 text-gray-700',
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">Tareas</h1>
          <p className="text-sm text-muted-foreground">Catálogo de tareas operativas</p>
        </div>
        <Button className="hidden sm:flex">
          <Plus className="h-4 w-4 mr-2" />
          Nueva Tarea
        </Button>
      </div>

      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
        <Input
          placeholder="Buscar tareas..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-10"
        />
      </div>

      {isLoading ? (
        <div className="space-y-3">
          {[...Array(5)].map((_, i) => (
            <Skeleton key={i} className="h-24 rounded-xl" />
          ))}
        </div>
      ) : (
        <div className="space-y-3">
          {tasks.map((task: any) => (
            <Card key={task.id} className="hover:shadow-md transition-shadow">
              <CardContent className="p-4">
                <div className="flex items-start justify-between gap-4">
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2 flex-wrap">
                      <h3 className="font-medium">{task.title}</h3>
                      <span className={`text-xs px-2 py-0.5 rounded-full font-medium ${priorityColors[task.priority] || ''}`}>
                        {task.priority}
                      </span>
                      {task.requires_evidence && (
                        <Badge variant="outline" className="text-xs">Requiere evidencia</Badge>
                      )}
                    </div>
                    <p className="text-sm text-muted-foreground mt-1 line-clamp-2">{task.description}</p>
                    <div className="flex items-center gap-3 mt-2 text-xs text-muted-foreground">
                      <span>{task.estimated_minutes} min</span>
                      <span>·</span>
                      <span className="capitalize">{task.category}</span>
                      <span>·</span>
                      <span>{task.active_assignments} activas</span>
                      <span>·</span>
                      <span>{task.completed_assignments} completadas</span>
                    </div>
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

export default TasksPage
