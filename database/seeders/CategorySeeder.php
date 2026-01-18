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
            [
                'name' => 'Alimentation',
                'name_translations' => [
                    'fr' => 'Alimentation',
                    'en' => 'Food',
                    'es' => 'Alimentación',
                    'pt' => 'Alimentação'
                ],
                'icon' => '🛒',
                'type' => 'expense',
                'color' => '#ef4444'
            ],
            [
                'name' => 'Transport',
                'name_translations' => [
                    'fr' => 'Transport',
                    'en' => 'Transportation',
                    'es' => 'Transporte',
                    'pt' => 'Transporte'
                ],
                'icon' => '🚗',
                'type' => 'expense',
                'color' => '#f59e0b'
            ],
            [
                'name' => 'Loyer',
                'name_translations' => [
                    'fr' => 'Loyer',
                    'en' => 'Rent',
                    'es' => 'Alquiler',
                    'pt' => 'Aluguel'
                ],
                'icon' => '🏠',
                'type' => 'expense',
                'color' => '#3b82f6'
            ],
            [
                'name' => 'Loisirs',
                'name_translations' => [
                    'fr' => 'Loisirs',
                    'en' => 'Entertainment',
                    'es' => 'Ocio',
                    'pt' => 'Lazer'
                ],
                'icon' => '🎮',
                'type' => 'expense',
                'color' => '#10b981'
            ],
            [
                'name' => 'Santé',
                'name_translations' => [
                    'fr' => 'Santé',
                    'en' => 'Health',
                    'es' => 'Salud',
                    'pt' => 'Saúde'
                ],
                'icon' => '🏥',
                'type' => 'expense',
                'color' => '#ec4899'
            ],
            [
                'name' => 'Shopping',
                'name_translations' => [
                    'fr' => 'Shopping',
                    'en' => 'Shopping',
                    'es' => 'Compras',
                    'pt' => 'Compras'
                ],
                'icon' => '🛍️',
                'type' => 'expense',
                'color' => '#8b5cf6'
            ],
            [
                'name' => 'Education',
                'name_translations' => [
                    'fr' => 'Éducation',
                    'en' => 'Education',
                    'es' => 'Educación',
                    'pt' => 'Educação'
                ],
                'icon' => '📚',
                'type' => 'expense',
                'color' => '#06b6d4'
            ],
            [
                'name' => 'Autres',
                'name_translations' => [
                    'fr' => 'Autres',
                    'en' => 'Other',
                    'es' => 'Otros',
                    'pt' => 'Outros'
                ],
                'icon' => '📦',
                'type' => 'expense',
                'color' => '#6b7280'
            ],

            // Revenues
            [
                'name' => 'Salaire',
                'name_translations' => [
                    'fr' => 'Salaire',
                    'en' => 'Salary',
                    'es' => 'Salario',
                    'pt' => 'Salário'
                ],
                'icon' => '💼',
                'type' => 'revenue',
                'color' => '#10b981'
            ],
            [
                'name' => 'Freelance',
                'name_translations' => [
                    'fr' => 'Freelance',
                    'en' => 'Freelance',
                    'es' => 'Freelance',
                    'pt' => 'Freelance'
                ],
                'icon' => '💻',
                'type' => 'revenue',
                'color' => '#3b82f6'
            ],
            [
                'name' => 'Cadeau',
                'name_translations' => [
                    'fr' => 'Cadeau',
                    'en' => 'Gift',
                    'es' => 'Regalo',
                    'pt' => 'Presente'
                ],
                'icon' => '🎁',
                'type' => 'revenue',
                'color' => '#ec4899'
            ],
            [
                'name' => 'Vente',
                'name_translations' => [
                    'fr' => 'Vente',
                    'en' => 'Sale',
                    'es' => 'Venta',
                    'pt' => 'Venda'
                ],
                'icon' => '💰',
                'type' => 'revenue',
                'color' => '#f59e0b'
            ],
            [
                'name' => 'Investissement',
                'name_translations' => [
                    'fr' => 'Investissement',
                    'en' => 'Investment',
                    'es' => 'Inversión',
                    'pt' => 'Investimento'
                ],
                'icon' => '📈',
                'type' => 'revenue',
                'color' => '#8b5cf6'
            ],
            [
                'name' => 'Autres',
                'name_translations' => [
                    'fr' => 'Autres',
                    'en' => 'Other',
                    'es' => 'Otros',
                    'pt' => 'Outros'
                ],
                'icon' => '➕',
                'type' => 'revenue',
                'color' => '#6b7280'
            ],

            // Savings
            [
                'name' => 'Voyage',
                'name_translations' => [
                    'fr' => 'Voyage',
                    'en' => 'Travel',
                    'es' => 'Viaje',
                    'pt' => 'Viagem'
                ],
                'icon' => '✈️',
                'type' => 'saving',
                'color' => '#3b82f6'
            ],
            [
                'name' => 'Urgence',
                'name_translations' => [
                    'fr' => 'Urgence',
                    'en' => 'Emergency',
                    'es' => 'Emergencia',
                    'pt' => 'Emergência'
                ],
                'icon' => '🚨',
                'type' => 'saving',
                'color' => '#ef4444'
            ],
            [
                'name' => 'Investissement',
                'name_translations' => [
                    'fr' => 'Investissement',
                    'en' => 'Investment',
                    'es' => 'Inversión',
                    'pt' => 'Investimento'
                ],
                'icon' => '🏛️',
                'type' => 'saving',
                'color' => '#10b981'
            ],
            [
                'name' => 'Achat Majeur',
                'name_translations' => [
                    'fr' => 'Achat Majeur',
                    'en' => 'Major Purchase',
                    'es' => 'Compra Mayor',
                    'pt' => 'Compra Importante'
                ],
                'icon' => '💎',
                'type' => 'saving',
                'color' => '#f59e0b'
            ],
            [
                'name' => 'Retraite',
                'name_translations' => [
                    'fr' => 'Retraite',
                    'en' => 'Retirement',
                    'es' => 'Jubilación',
                    'pt' => 'Aposentadoria'
                ],
                'icon' => '👴',
                'type' => 'saving',
                'color' => '#ec4899'
            ],
            [
                'name' => 'Autres',
                'name_translations' => [
                    'fr' => 'Autres',
                    'en' => 'Other',
                    'es' => 'Otros',
                    'pt' => 'Outros'
                ],
                'icon' => '🐷',
                'type' => 'saving',
                'color' => '#6b7280'
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}
