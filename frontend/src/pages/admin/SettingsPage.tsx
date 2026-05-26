import { useQuery } from '@tanstack/react-query'
import { Settings, Building, Clock, Shield, Palette } from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function SettingsPage() {
  const { data, isLoading } = useQuery({
    queryKey: ['settings'],
    queryFn: async () => {
      const res = await api.get('/settings')
      return res.data
    },
  })

  const settings = data?.data || {}

  const icons: Record<string, React.ReactNode> = {
    company: <Building className="h-5 w-5" />,
    schedule: <Clock className="h-5 w-5" />,
    security: <Shield className="h-5 w-5" />,
    ui: <Palette className="h-5 w-5" />,
    general: <Settings className="h-5 w-5" />,
  }

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold">Configuración</h1>
        <p className="text-sm text-muted-foreground">Ajustes del sistema y reglas laborales</p>
      </div>

      {isLoading ? (
        <div className="space-y-4">
          {[...Array(4)].map((_, i) => (
            <Skeleton key={i} className="h-48 rounded-xl" />
          ))}
        </div>
      ) : (
        Object.entries(settings).map(([category, items]: [string, any]) => (
          <Card key={category}>
            <CardHeader>
              <CardTitle className="flex items-center gap-2 text-base capitalize">
                {icons[category] || <Settings className="h-5 w-5" />}
                {category.replace('_', ' ')}
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-3">
                {items.map((setting: any) => (
                  <div
                    key={setting.key}
                    className="flex items-center justify-between p-3 rounded-lg bg-secondary/30"
                  >
                    <div>
                      <p className="font-medium text-sm">{setting.key.replace('_', ' ')}</p>
                      <p className="text-xs text-muted-foreground">{setting.description || setting.type}</p>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-medium">
                        {typeof setting.value === 'boolean'
                          ? (setting.value ? 'Sí' : 'No')
                          : typeof setting.value === 'object'
                          ? JSON.stringify(setting.value)
                          : String(setting.value)}
                      </p>
                      {!setting.is_editable && (
                        <p className="text-xs text-muted-foreground">Solo lectura</p>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        ))
      )}
    </div>
  )
}

export default SettingsPage
