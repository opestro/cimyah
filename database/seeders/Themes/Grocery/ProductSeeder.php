<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\ProductSeeder as MainProductSeeder;

class ProductSeeder extends MainProductSeeder
{
    protected function getProducts(): array
    {
        return [
            'Organic Apples',
            'Whole Grain Bread',
            'Free-Range Eggs',
            'Fresh Salmon Fillet',
            'Organic Spinach',
            'Grass-Fed Ground Beef',
            'Almond Milk',
            'Quinoa',
            'Avocado',
            'Greek Yogurt',
            'Sweet Potatoes',
            'Organic Cherry Tomatoes',
            'Honeycrisp Apples',
            'Brown Rice',
            'Organic Chicken Breast',
            'Extra Virgin Olive Oil',
            'Cucumber',
            'Black Beans',
            'Chia Seeds',
            'Whole Wheat Pasta',
            'Mixed Nuts',
            'Green Tea Bags',
            'Organic Strawberries',
            'Quinoa Flour',
            'Organic Baby Kale',
            'Salad Dressing',
            'Sweet Corn',
            'Almond Butter',
            'Whole Pineapple',
            'Cottage Cheese',
        ];
    }

    protected function getDescriptions(): array
    {
        return [
            'Fresh and crisp organic apples for a healthy snack or delicious recipes.',
            'Whole wheat pasta, a healthier alternative with a nutty flavor.',
            'A mix of nuts for a tasty and energy-boosting trail mix.',
            'Green tea bags for a soothing and antioxidant-rich beverage.',
            'Sweet and juicy organic strawberries for a delightful treat.',
            'Quinoa flour, a gluten-free alternative for baking.',
            'Organic baby kale, a nutrient-packed green for salads and smoothies.',
            'Balsamic salad dressing to enhance the flavor of your salads.',
            'Sweet corn, a delicious and versatile vegetable.',
            'Creamy almond butter for a tasty and nutritious spread.',
            'Whole pineapple for a tropical and refreshing treat.',
            'Cottage cheese, a protein-rich and versatile dairy product.',
        ];
    }
}
