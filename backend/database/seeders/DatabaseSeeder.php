<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AccessProfileSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeTypeSeeder::class,
            ShiftSeeder::class,
            LegalRuleSeeder::class,
            SettingSeeder::class,
            BusinessSeasonSeeder::class,
            HolidaySeeder::class,
            PersonSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            CandidateSeeder::class,
            TaskSeeder::class,
            RoutineSeeder::class,
            VacationBalanceSeeder::class,
            HistoricalBenefitSeeder::class,
            AttendanceSeeder::class,
            AccountSuspensionSeeder::class,
            AdminWarningSeeder::class,
            TaskAssignmentSeeder::class,
            RoutineAssignmentSeeder::class,
        ]);
    }
}
