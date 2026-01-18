<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Expenses
            ['name' => 'Alimentation', 'icon' => '🛒', 'type' => 'expense', 'color' => '#ef4444'],
            ['name' => 'Transport', 'icon' => '🚗', 'type' => 'expense', 'color' => '#f59e0b'],
            ['name' => 'Loyer', 'icon' => '🏠', 'type' => 'expense', 'color' => '#3b82f6'],
            ['name' => 'Loisirs', 'icon' => '🎮', 'type' => 'expense', 'color' => '#10b981'],
            ['name' => 'Santé', 'icon' => '🏥', 'type' => 'expense', 'color' => '#ec4899'],
            ['name' => 'Shopping', 'icon' => '🛍️', 'type' => 'expense', 'color' => '#8b5cf6'],
            ['name' => 'Education', 'icon' => '📚', 'type' => 'expense', 'color' => '#06b6d4'],
            ['name' => 'Autres', 'icon' => '📦', 'type' => 'expense', 'color' => '#6b7280'],

            // Revenues
            ['name' => 'Salaire', 'icon' => '💼', 'type' => 'revenue', 'color' => '#10b981'],
            ['name' => 'Freelance', 'icon' => '💻', 'type' => 'revenue', 'color' => '#3b82f6'],
            ['name' => 'Cadeau', 'icon' => '🎁', 'type' => 'revenue', 'color' => '#ec4899'],
            ['name' => 'Vente', 'icon' => '💰', 'type' => 'revenue', 'color' => '#f59e0b'],
            ['name' => 'Investissement', 'icon' => '📈', 'type' => 'revenue', 'color' => '#8b5cf6'],
            ['name' => 'Autres', 'icon' => '➕', 'type' => 'revenue', 'color' => '#6b7280'],

            // Savings
            ['name' => 'Voyage', 'icon' => '✈️', 'type' => 'saving', 'color' => '#3b82f6'],
            ['name' => 'Urgence', 'icon' => '🚨', 'type' => 'saving', 'color' => '#ef4444'],
            ['name' => 'Investissement', 'icon' => '🏛️', 'type' => 'saving', 'color' => '#10b981'],
            ['name' => 'Achat Majeur', 'icon' => '💎', 'type' => 'saving', 'color' => '#f59e0b'],
            ['name' => 'Retraite', 'icon' => '👴', 'type' => 'saving', 'color' => '#ec4899'],
            ['name' => 'Autres', 'icon' => '🐷', 'type' => 'saving', 'color' => '#6b7280'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}
