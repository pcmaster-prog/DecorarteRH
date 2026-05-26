<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\AccessProfile;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador General - Marisol Ramos (Person ID 1)
        $admin = User::create([
            'person_id' => 1,
            'email' => 'admin@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $adminRole = Role::where('name', 'administrador_general')->first();
        $admin->roles()->attach($adminRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $admin->update(['primary_role_id' => $adminRole->id]);
        $adminProfile = AccessProfile::where('name', 'administrador_total')->first();
        $admin->accessProfiles()->attach($adminProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Gerente - Francisco Vega (Person ID 2)
        $gerente = User::create([
            'person_id' => 2,
            'email' => 'gerente@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $gerenteRole = Role::where('name', 'gerente')->first();
        $gerente->roles()->attach($gerenteRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $gerente->update(['primary_role_id' => $gerenteRole->id]);
        $gerenteProfile = AccessProfile::where('name', 'gerente_operativo')->first();
        $gerente->accessProfiles()->attach($gerenteProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Supervisora Cajas - Ana López (Person ID 3)
        $ana = User::create([
            'person_id' => 3,
            'email' => 'ana.supervisor@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $ana->roles()->attach($supervisorRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $ana->update(['primary_role_id' => $supervisorRole->id]);
        $supervisorProfile = AccessProfile::where('name', 'supervisor_avanzado')->first();
        $ana->accessProfiles()->attach($supervisorProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Supervisor Producción - Luis Martínez (Person ID 4)
        $luis = User::create([
            'person_id' => 4,
            'email' => 'luis.supervisor@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $luis->roles()->attach($supervisorRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $luis->update(['primary_role_id' => $supervisorRole->id]);
        $luis->accessProfiles()->attach($supervisorProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Permiso temporal para Luis como Gerente Operativo en Producción (7 días)
        $tempRole = Role::where('name', 'gerente')->first();
        $luis->roles()->attach($tempRole->id, [
            'is_primary' => false,
            'granted_at' => now(),
            'granted_by' => 1,
            'expires_at' => now()->addDays(7),
        ]);

        // Supervisora Atención - Claudia Hernández (Person ID 5)
        $claudia = User::create([
            'person_id' => 5,
            'email' => 'claudia.supervisor@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $claudia->roles()->attach($supervisorRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        // Rol adicional: RH Operativo limitado
        $rhRole = Role::where('name', 'recursos_humanos')->first();
        $claudia->roles()->attach($rhRole->id, [
            'is_primary' => false,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $claudia->update(['primary_role_id' => $supervisorRole->id]);
        $claudia->accessProfiles()->attach($supervisorProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $rhProfile = AccessProfile::where('name', 'rh_operativo')->first();
        $claudia->accessProfiles()->attach($rhProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Empleado 1 - Juan Pérez (Person ID 6)
        $juan = User::create([
            'person_id' => 6,
            'email' => 'juan.perez@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $empleadoRole = Role::where('name', 'empleado')->first();
        $juan->roles()->attach($empleadoRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $juan->update(['primary_role_id' => $empleadoRole->id]);
        $empleadoProfile = AccessProfile::where('name', 'empleado_base')->first();
        $juan->accessProfiles()->attach($empleadoProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Empleado 2 - María González (Person ID 7)
        $maria = User::create([
            'person_id' => 7,
            'email' => 'maria.gonzalez@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $maria->roles()->attach($empleadoRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $maria->update(['primary_role_id' => $empleadoRole->id]);
        $maria->accessProfiles()->attach($empleadoProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Empleado 3 - Pedro Ramírez (Person ID 8) - SUSPENDIDO
        $pedro = User::create([
            'person_id' => 8,
            'email' => 'pedro.ramirez@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => false,
            'email_verified_at' => now(),
        ]);
        $pedro->roles()->attach($empleadoRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $pedro->update(['primary_role_id' => $empleadoRole->id]);
        $pedro->accessProfiles()->attach($empleadoProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Empleado 4 - Sofía Torres (Person ID 9)
        $sofia = User::create([
            'person_id' => 9,
            'email' => 'sofia.torres@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $sofia->roles()->attach($empleadoRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $sofia->update(['primary_role_id' => $empleadoRole->id]);
        $sofia->accessProfiles()->attach($empleadoProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Empleado 5 - Diego Morales (Person ID 10) - Por Hora
        $diego = User::create([
            'person_id' => 10,
            'email' => 'diego.morales@decorarte.demo',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $hourlyRole = Role::where('name', 'empleado_por_hora')->first();
        $diego->roles()->attach($hourlyRole->id, [
            'is_primary' => true,
            'granted_at' => now(),
            'granted_by' => 1,
        ]);
        $diego->update(['primary_role_id' => $hourlyRole->id]);
        $hourlyProfile = AccessProfile::where('name', 'empleado_por_hora')->first();
        $diego->accessProfiles()->attach($hourlyProfile->id, [
            'granted_at' => now(),
            'granted_by' => 1,
        ]);

        // Candidatos no tienen usuarios de sistema hasta ser contratados
    }
}
