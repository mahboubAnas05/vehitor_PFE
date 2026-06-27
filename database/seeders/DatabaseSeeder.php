<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- 1. ADMIN ACCOUNT ----------
        // this is the only way an admin account gets created (not through the register form)
        $admin = User::create([
            'name' => 'Admin Vehitor',
            'email' => 'admin@vehitor.com',
            'password' => Hash::make('password'), // password to use for login: "password"
            'role' => 'admin',
            'phone' => '0600000000',
        ]);

        // ---------- 2. AGENCIES (with their user accounts) ----------
        // we create 3 agencies: 2 approved, 1 still pending (good for demo of admin approval feature)
        $agenciesData = [
            [
                'name' => 'nizar Rent Car',
                'city' => 'Casablanca',
                'address' => '12 Boulevard Zerktouni',
                'status' => 'approved',
            ],
            [
                'name' => 'Marrakech Auto Location',
                'city' => 'Marrakech',
                'address' => '45 Avenue Mohammed V',
                'status' => 'approved',
            ],
            [
                'name' => 'Rabat Drive',
                'city' => 'Rabat',
                'address' => '8 Rue Patrice Lumumba',
                'status' => 'pending', // this one will show up in admin's "pending approval" list
            ],
        ];

        $agencies = [];

        foreach ($agenciesData as $index => $data) {
            // create the login account for the agency first
            $user = User::create([
                'name' => $data['name'].' Manager',
                'email' => 'agence'.($index + 1).'@vehitor.com',
                'password' => Hash::make('password'),
                'role' => 'agency',
                'phone' => '06000000'.($index + 1).'0',
            ]);

            // then create the agency profile linked to that user
            $agencies[] = Agency::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'address' => $data['address'],
                'city' => $data['city'],
                'description' => 'Agence de location de voitures fiable basée à '.$data['city'].'.',
                'status' => $data['status'],
            ]);
        }

        // ---------- 3. CLIENTS ----------
        $clients = [];

        $clientsData = [
            ['name' => 'Youssef Amrani', 'email' => 'client1@vehitor.com'],
            ['name' => 'Sara Bennani', 'email' => 'client2@vehitor.com'],
            ['name' => 'Karim El Idrissi', 'email' => 'client3@vehitor.com'],
        ];

        foreach ($clientsData as $index => $data) {
            $clients[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '06111111'.$index,
            ]);
        }

        // ---------- 4. CARS ----------
        // only the 2 APPROVED agencies get cars (agencies[0] and agencies[1])
        $carsData = [
            ['brand' => 'Dacia', 'model' => 'Logan', 'year' => 2022, 'transmission' => 'manuelle', 'fuel_type' => 'diesel', 'seats' => 5, 'price_per_day' => 250],
            ['brand' => 'Renault', 'model' => 'Clio', 'year' => 2023, 'transmission' => 'manuelle', 'fuel_type' => 'essence', 'seats' => 5, 'price_per_day' => 280],
            ['brand' => 'Volkswagen', 'model' => 'Golf', 'year' => 2021, 'transmission' => 'automatique', 'fuel_type' => 'diesel', 'seats' => 5, 'price_per_day' => 400],
            ['brand' => 'Hyundai', 'model' => 'Tucson', 'year' => 2023, 'transmission' => 'automatique', 'fuel_type' => 'essence', 'seats' => 5, 'price_per_day' => 550],
            ['brand' => 'Fiat', 'model' => '500', 'year' => 2022, 'transmission' => 'manuelle', 'fuel_type' => 'essence', 'seats' => 4, 'price_per_day' => 220],
            ['brand' => 'Tesla', 'model' => 'Model 3', 'year' => 2024, 'transmission' => 'automatique', 'fuel_type' => 'electrique', 'seats' => 5, 'price_per_day' => 800],
        ];

        $cars = [];

        foreach ($carsData as $index => $data) {
            // alternate cars between the 2 approved agencies
            $agency = $index % 2 === 0 ? $agencies[0] : $agencies[1];

            $cars[] = Car::create(array_merge($data, [
                'agency_id' => $agency->id,
                'description' => $data['brand'].' '.$data['model'].' en excellent état, idéale pour vos déplacements.',
                'is_available' => true,
            ]));
        }

        // ---------- 5. BOOKINGS ----------
        // create a few example bookings with different statuses
        Booking::create([
            'client_id' => $clients[0]->id,
            'car_id' => $cars[0]->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(5),
            'total_price' => $cars[0]->price_per_day * 4,
            'status' => 'pending',
        ]);

        Booking::create([
            // 'client_id' => $clients[1]->id,
            'car_id' => $cars[2]->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(3),
            'total_price' => $cars[2]->price_per_day * 3,
            'status' => 'confirmed',
        ]);

        // a completed booking in the past, so we can demo the review feature
        $completedBooking = Booking::create([
            'client_id' => $clients[2]->id,
            'car_id' => $cars[1]->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(7),
            'total_price' => $cars[1]->price_per_day * 4,
            'status' => 'completed',
        ]);

        // ---------- 6. REVIEWS ----------
        Review::create([
            'client_id' => $clients[2]->id,
            'car_id' => $cars[1]->id,
            'rating' => 5,
            'comment' => 'Très bonne voiture, propre et bien entretenue. Agence très professionnelle.',
        ]);

        Review::create([
            'client_id' => $clients[0]->id,
            'car_id' => $cars[0]->id,
            'rating' => 4,
            'comment' => 'Bon rapport qualité/prix, je recommande.',
        ]);

        $this->command->info('Données de démonstration créées avec succès !');
        $this->command->info('--- Comptes de test (mot de passe pour tous : password) ---');
        $this->command->info('Admin    : admin@vehitor.com');
        $this->command->info('Agence 1 (approuvée) : agence1@vehitor.com');
        $this->command->info('Agence 2 (approuvée) : agence2@vehitor.com');
        $this->command->info('Agence 3 (en attente): agence3@vehitor.com');
        $this->command->info('Client 1 : client1@vehitor.com');
    }
}
