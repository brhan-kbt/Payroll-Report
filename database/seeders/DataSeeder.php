<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if not exists
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);


        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Employee::create([
                'name' => $faker->name,
                'employee_id' => strtoupper($faker->unique()->bothify('EMP###')),
                'department' => $faker->randomElement(['HR', 'Finance', 'IT', 'Marketing', 'Sales']),
                'position' => $faker->jobTitle,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'date_of_joining' => $faker->date('Y-m-d', 'now'),
                'date_of_birth' => $faker->date('Y-m-d', '-22 years'),
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'address' => $faker->address,
                'is_active' => $faker->boolean(90), // 90% chance active
            ]);
        }
        $this->command->info('Data seeded successfully!');
        $this->command->info('Test user: admin@blog.com / password');
    }
}
