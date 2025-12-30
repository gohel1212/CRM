<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\Note;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'darsha@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create a regular user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Create sample customers
        $customers = [
            [
                'name' => 'Acme Corporation',
                'email' => 'info@acme.com',
                'phone' => '(555) 123-4567',
                'address' => '123 Main St',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'website' => 'https://www.acme.com',
                'description' => 'A leading technology company',
                'status' => 'active'
            ],
            [
                'name' => 'Global Industries',
                'email' => 'contact@globalind.com',
                'phone' => '(555) 987-6543',
                'address' => '456 Oak Ave',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'website' => 'https://www.globalindustries.com',
                'description' => 'International manufacturing company',
                'status' => 'active'
            ]
        ];

        // Ensure a default pipeline with common stages exists
        $pipeline = Pipeline::first() ?: Pipeline::create([
            'name' => 'Default Pipeline',
            'description' => 'Standard sales pipeline',
            'is_active' => true,
            'order' => 0,
            'created_by' => 1,
        ]);

        if ($pipeline->stages()->count() === 0) {
            $defaultStages = [
                ['name' => 'Qualified', 'probability' => 10, 'order' => 1, 'color' => '#64748b'],
                ['name' => 'Contact Made', 'probability' => 20, 'order' => 2, 'color' => '#22c55e'],
                ['name' => 'Demo Scheduled', 'probability' => 40, 'order' => 3, 'color' => '#eab308'],
                ['name' => 'Proposal Made', 'probability' => 60, 'order' => 4, 'color' => '#8b5cf6'],
                ['name' => 'Negotiations Started', 'probability' => 75, 'order' => 5, 'color' => '#f97316'],
                ['name' => 'Deal Closed', 'probability' => 100, 'order' => 6, 'color' => '#10b981'],
            ];
            foreach ($defaultStages as $s) {
                PipelineStage::create(array_merge($s, [
                    'pipeline_id' => $pipeline->id,
                    'is_active' => true,
                ]));
            }
        }

        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);

            // Create contacts for each customer
            Contact::create([
                'customer_id' => $customer->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@' . $customer->email,
                'phone' => '(555) 111-2222',
                'position' => 'CEO',
                'department' => 'Executive',
                'is_primary' => true
            ]);

            // Create deals for each customer
            $firstStage = $pipeline->stages()->orderBy('order')->first();
            Deal::create([
                'customer_id' => $customer->id,
                'name' => 'New Project Proposal',
                'description' => 'Proposal for software development project',
                'amount' => rand(10000, 50000),
                'currency' => 'USD',
                'status' => 'open',
                'expected_close_date' => now()->addDays(30),
                'owner_id' => $admin->id,
                'pipeline_id' => $pipeline->id,
                'pipeline_stage_id' => optional($firstStage)->id,
            ]);

            // Create activities for each customer
            Activity::create([
                'type' => 'meeting',
                'subject' => 'Initial Meeting',
                'description' => 'Discuss project requirements',
                'due_date' => now()->addDays(7),
                'status' => 'pending',
                'assigned_to' => $user->id,
                'created_by' => $admin->id,
                'activityable_type' => 'App\\Models\\Customer',
                'activityable_id' => $customer->id
            ]);

            // Create notes for each customer
            Note::create([
                'customer_id' => $customer->id,
                'content' => 'Initial contact made. Customer shows high interest.',
                'created_by' => $user->id
            ]);
        }
    }
}
