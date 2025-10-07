<?php
// database/seeders/ExpenseSeeder.php
namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();
        $categories = [
            'maintenance' => 'Maintenance & Repairs',
            'utility' => 'Utilities (Water, Electricity)',
            'staff' => 'Staff Salaries',
            'insurance' => 'Insurance',
            'tax' => 'Taxes',
            'security' => 'Security',
            'cleaning' => 'Cleaning Services',
            'management' => 'Management Fees',
            'other' => 'Other Expenses'
        ];

        foreach ($properties as $property) {
            // Create expenses for the last 3 months
            for ($i = 0; $i < 3; $i++) {
                $expenseDate = Carbon::now()->subMonths($i);
                
                // 2-4 expenses per property per month
                $numExpenses = rand(2, 4);
                
                for ($j = 0; $j < $numExpenses; $j++) {
                    $category = array_rand($categories);
                    
                    Expense::create([
                        'property_id' => $property->id,
                        'category' => $category,
                        'description' => $this->generateExpenseDescription($category),
                        'amount' => $this->generateExpenseAmount($category),
                        'expense_date' => $expenseDate->subDays(rand(0, 30)),
                        'receipt_number' => 'EXP' . rand(10000, 99999),
                        'notes' => $this->generateExpenseNotes($category),
                    ]);
                }
            }
        }

        $this->command->info('Expenses seeded successfully!');
    }

    private function generateExpenseDescription($category)
    {
        $descriptions = [
            'maintenance' => ['Plumbing repairs', 'Electrical maintenance', 'Painting work', 'Carpentry repairs', 'General maintenance'],
            'utility' => ['Water bill payment', 'Electricity bill', 'Sewer charges', 'Garbage collection'],
            'staff' => ['Caretaker salary', 'Security guard payment', 'Cleaner wages', 'Maintenance staff salary'],
            'insurance' => ['Property insurance', 'Liability insurance', 'Equipment insurance'],
            'tax' => ['Property rates', 'Business permit', 'Withholding tax'],
            'security' => ['Security service', 'CCTV maintenance', 'Alarm system'],
            'cleaning' => ['Common area cleaning', 'Window cleaning', 'Garden maintenance'],
            'management' => ['Management fees', 'Administrative costs', 'Professional fees'],
            'other' => ['Office supplies', 'Transport costs', 'Miscellaneous expenses']
        ];

        $options = $descriptions[$category] ?? ['General expense'];
        return $options[array_rand($options)];
    }

    private function generateExpenseAmount($category)
    {
        $ranges = [
            'maintenance' => [500, 20000],
            'utility' => [1000, 15000],
            'staff' => [5000, 30000],
            'insurance' => [5000, 50000],
            'tax' => [1000, 25000],
            'security' => [3000, 20000],
            'cleaning' => [1000, 8000],
            'management' => [5000, 25000],
            'other' => [200, 5000]
        ];

        $range = $ranges[$category] ?? [100, 5000];
        return rand($range[0], $range[1]);
    }

    private function generateExpenseNotes($category)
    {
        $notes = [
            'maintenance' => 'Routine maintenance work completed',
            'utility' => 'Monthly utility bill payment',
            'staff' => 'Staff salary for the month',
            'insurance' => 'Annual insurance premium',
            'tax' => 'Tax payment as required',
            'security' => 'Security services rendered',
            'cleaning' => 'Cleaning services provided',
            'management' => 'Management and administrative fees',
            'other' => 'Miscellaneous expense incurred'
        ];

        return $notes[$category] ?? 'Expense recorded';
    }
}