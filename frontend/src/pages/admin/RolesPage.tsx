import { useQuery } from '@tanstack/react-query'
import { Shield, Users, Key, Fingerprint } from 'lucide-react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Skeleton } from '@/components/ui/Skeleton'
import { api } from '@/services/api'

function RolesPage() {
  const { data: rolesData, isLoading: rolesLoading } = useQuery({
    queryKey: ['roles'],
    queryFn: async () => {
      const res = await api.get('/roles')
      return res.data
    },
  })

  const { data: permissionsData, isLoading: permissionsLoading } = useQuery({
    queryKey: ['permissions'],
    queryFn: async () => {
      const res = await api.get('/permissions')
      return res.data
    },
  })

  const { data: profilesData, isLoading: profilesLoading } = useQuery({
    queryKey: ['access-profiles'],
    queryFn: async () => {
      const res = await api.get('/access-profiles')
      return res.data
    },
  })

  const roles = rolesData?.data || []
  const permissions = permissionsData?.data || {}
  const profiles = profilesData?.data || []

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold">Roles y Permisos</h1>
        <p className="text-sm text-muted-foreground">Gestión de roles, permisos y perfiles de acceso</p>
      </div>

      {/* Roles */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Shield className="h-5 w-5 text-primary" />
            Roles del Sistema
          </CardTitle>
        </CardHeader>
        <CardContent>
          {rolesLoading ? (
            <Skeleton className="h-32 rounded-xl" />
          ) : (
            <div className="space-y-3">
              {roles.map((role: any) => (
                <div
                  key={role.id}
                  className="flex items-center justify-between p-3 rounded-lg bg-secondary/50"
                >
                  <div>
                    <div className="flex items-center gap-2">
                      <p className="font-medium">{role.display_name}</p>
                      <Badge variant="secondary" className="text-xs">Nivel {role.level}</Badge>
                      {role.is_system && <Badge variant="outline" className="text-xs">Sistema</Badge>}
                    </div>
                    <p className="text-sm text-muted-foreground">{role.description}</p>
                  </div>
                  <div className="text-right text-sm text-muted-foreground">
                    <p>{role.users_count} usuarios</p>
                    <p>{role.permissions_count} permisos</p>
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Access Profiles */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Fingerprint className="h-5 w-5 text-primary" />
            Perfiles de Acceso
          </CardTitle>
        </CardHeader>
        <CardContent>
          {profilesLoading ? (
            <Skeleton className="h-32 rounded-xl" />
          ) : (
            <div className="space-y-3">
              {profiles.map((profile: any) => (
                <div
                  key={profile.id}
                  className="flex items-center justify-between p-3 rounded-lg bg-secondary/50"
                >
                  <div>
                    <div className="flex items-center gap-2">
                      <p className="font-medium">{profile.display_name}</p>
                      {profile.is_system && <Badge variant="outline" className="text-xs">Sistema</Badge>}
                    </div>
                    <p className="text-sm text-muted-foreground">{profile.description}</p>
                  </div>
                  <div className="text-right text-sm text-muted-foreground">
                    <p>{profile.users_count} usuarios</p>
                    <p>{profile.permissions_count} permisos</p>
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Permissions by Module */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Key className="h-5 w-5 text-primary" />
            Permisos por Módulo
          </CardTitle>
        </CardHeader>
        <CardContent>
          {permissionsLoading ? (
            <Skeleton className="h-64 rounded-xl" />
          ) : (
            <div className="space-y-4">
              {Object.entries(permissions).map(([module, perms]: [string, any]) => (
                <div key={module}>
                  <h4 className="font-medium capitalize mb-2 text-sm">{module.replace('_', ' ')}</h4>
                  <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    {perms.map((perm: any) => (
                      <div
                        key={perm.id}
                        className={`p-2 rounded-lg text-xs border ${
                          perm.is_critical
                            ? 'border-red-200 bg-red-50 dark:bg-red-900/10'
                            : 'border-border bg-secondary/30'
                        }`}
                      >
                        <p className="font-medium">{perm.display_name}</p>
                        <p className="text-muted-foreground">{perm.action}</p>
                        {perm.is_critical && (
                          <Badge variant="destructive" className="text-[10px] mt-1">Crítico</Badge>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  )
}

export default RolesPage
