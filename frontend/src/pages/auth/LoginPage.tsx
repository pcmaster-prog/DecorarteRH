import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { Cake, Eye, EyeOff, Loader2 } from 'lucide-react'
import { Button } from '@/components/ui/Button'
import { Input } from '@/components/ui/Input'
import { Label } from '@/components/ui/Label'
import { useAuthStore } from '@/stores/authStore'
import { api } from '@/services/api'
import toast from 'react-hot-toast'

function LoginPage() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [showPassword, setShowPassword] = useState(false)
  const [isLoading, setIsLoading] = useState(false)
  const { setAuth } = useAuthStore()
  const navigate = useNavigate()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      const response = await api.post('/auth/login', { email, password })
      const { token, user } = response.data

      setAuth(user, token)
      toast.success(`Bienvenido, ${user.name}`)
      navigate('/')
    } catch (error: any) {
      const message = error.response?.data?.message || 'Error al iniciar sesión'
      toast.error(message)
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-decorarte-50 to-decorarte-100 dark:from-gray-900 dark:to-gray-800 p-4">
      <div className="w-full max-w-md">
        {/* Logo */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-decorarte-600 shadow-lg mb-4">
            <Cake className="h-8 w-8 text-white" />
          </div>
          <h1 className="text-2xl font-bold text-foreground">DecorArte</h1>
          <p className="text-muted-foreground mt-1">RH Operativo</p>
        </div>

        {/* Card */}
        <div className="bg-card rounded-2xl shadow-xl border p-6 md:p-8">
          <h2 className="text-xl font-semibold mb-1">Iniciar sesión</h2>
          <p className="text-sm text-muted-foreground mb-6">
            Ingresa tus credenciales para acceder
          </p>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="email">Correo electrónico</Label>
              <Input
                id="email"
                type="email"
                placeholder="tu@email.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="h-11"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Contraseña</Label>
              <div className="relative">
                <Input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  className="h-11 pr-10"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                >
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
              </div>
            </div>

            <Button
              type="submit"
              className="w-full h-11 text-base"
              isLoading={isLoading}
            >
              {isLoading ? 'Iniciando...' : 'Iniciar sesión'}
            </Button>
          </form>

          {/* Demo accounts */}
          <div className="mt-6 pt-6 border-t">
            <p className="text-xs text-muted-foreground text-center mb-3">
              Cuentas de demostración
            </p>
            <div className="grid grid-cols-2 gap-2">
              <button
                onClick={() => { setEmail('admin@decorarte.demo'); setPassword('password') }}
                className="text-xs p-2 rounded-lg bg-secondary hover:bg-secondary/80 transition-colors text-left"
              >
                <span className="font-medium">Admin</span>
                <span className="block text-muted-foreground">admin@decorarte.demo</span>
              </button>
              <button
                onClick={() => { setEmail('gerente@decorarte.demo'); setPassword('password') }}
                className="text-xs p-2 rounded-lg bg-secondary hover:bg-secondary/80 transition-colors text-left"
              >
                <span className="font-medium">Gerente</span>
                <span className="block text-muted-foreground">gerente@decorarte.demo</span>
              </button>
              <button
                onClick={() => { setEmail('juan.perez@decorarte.demo'); setPassword('password') }}
                className="text-xs p-2 rounded-lg bg-secondary hover:bg-secondary/80 transition-colors text-left"
              >
                <span className="font-medium">Empleado</span>
                <span className="block text-muted-foreground">juan.perez@...</span>
              </button>
              <button
                onClick={() => { setEmail('diego.morales@decorarte.demo'); setPassword('password') }}
                className="text-xs p-2 rounded-lg bg-secondary hover:bg-secondary/80 transition-colors text-left"
              >
                <span className="font-medium">Por Hora</span>
                <span className="block text-muted-foreground">diego.morales@...</span>
              </button>
            </div>
          </div>
        </div>

        <p className="text-center text-xs text-muted-foreground mt-6">
          DecorArte RH Operativo v1.0 · Sistema de Recursos Humanos
        </p>
      </div>
    </div>
  )
}

export default LoginPage
