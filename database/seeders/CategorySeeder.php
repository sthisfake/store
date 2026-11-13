<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computers',
                'children' => [
                    [
                        'name' => 'Laptops',
                        'children' => [
                            ['name' => 'Gaming Laptops'],
                            ['name' => 'Business Laptops'],
                            ['name' => 'Ultrabooks'],
                        ],
                    ],
                    [
                        'name' => 'Desktop Computers',
                        'children' => [
                            ['name' => 'Gaming Desktops'],
                            ['name' => 'Workstations'],
                            ['name' => 'All-in-One Computers'],
                        ],
                    ],
                    [
                        'name' => 'Computer Components',
                        'children' => [
                            ['name' => 'Processors'],
                            ['name' => 'Graphics Cards'],
                            ['name' => 'Motherboards'],
                            ['name' => 'Computer Memory'],
                            ['name' => 'Storage Drives'],
                            ['name' => 'Power Supplies'],
                            ['name' => 'Computer Cases'],
                            ['name' => 'Cooling'],
                        ],
                    ],
                    [
                        'name' => 'Computer Accessories',
                        'children' => [
                            ['name' => 'Keyboards'],
                            ['name' => 'Mice'],
                            ['name' => 'Webcams'],
                            ['name' => 'Laptop Bags and Stands'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Mobile Devices',
                'children' => [
                    [
                        'name' => 'Smartphones',
                        'children' => [
                            ['name' => 'Android Phones'],
                            ['name' => 'iPhones'],
                        ],
                    ],
                    [
                        'name' => 'Tablets',
                        'children' => [
                            ['name' => 'Android Tablets'],
                            ['name' => 'iPads'],
                        ],
                    ],
                    [
                        'name' => 'Mobile Accessories',
                        'children' => [
                            ['name' => 'Phone Cases'],
                            ['name' => 'Chargers and Cables'],
                            ['name' => 'Power Banks'],
                            ['name' => 'Screen Protectors'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Gaming',
                'children' => [
                    [
                        'name' => 'Gaming Consoles',
                        'children' => [
                            ['name' => 'PlayStation'],
                            ['name' => 'Xbox'],
                            ['name' => 'Nintendo'],
                        ],
                    ],
                    [
                        'name' => 'Gaming Accessories',
                        'children' => [
                            ['name' => 'Controllers'],
                            ['name' => 'Gaming Keyboards'],
                            ['name' => 'Gaming Mice'],
                            ['name' => 'Gaming Headsets'],
                        ],
                    ],
                    [
                        'name' => 'Video Games',
                        'children' => [
                            ['name' => 'PC Games'],
                            ['name' => 'Console Games'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Audio',
                'children' => [
                    [
                        'name' => 'Headphones',
                        'children' => [
                            ['name' => 'Wireless Headphones'],
                            ['name' => 'Wired Headphones'],
                            ['name' => 'Earbuds'],
                        ],
                    ],
                    [
                        'name' => 'Speakers',
                        'children' => [
                            ['name' => 'Bluetooth Speakers'],
                            ['name' => 'Smart Speakers'],
                            ['name' => 'Soundbars'],
                        ],
                    ],
                    ['name' => 'Microphones'],
                ],
            ],
            [
                'name' => 'Televisions and Displays',
                'children' => [
                    [
                        'name' => 'Televisions',
                        'children' => [
                            ['name' => 'Smart TVs'],
                            ['name' => 'OLED TVs'],
                            ['name' => 'QLED TVs'],
                        ],
                    ],
                    [
                        'name' => 'Monitors',
                        'children' => [
                            ['name' => 'Gaming Monitors'],
                            ['name' => 'Professional Monitors'],
                            ['name' => 'Ultrawide Monitors'],
                        ],
                    ],
                    ['name' => 'Projectors'],
                ],
            ],
            [
                'name' => 'Cameras',
                'children' => [
                    [
                        'name' => 'Digital Cameras',
                        'children' => [
                            ['name' => 'Mirrorless Cameras'],
                            ['name' => 'DSLR Cameras'],
                            ['name' => 'Action Cameras'],
                        ],
                    ],
                    [
                        'name' => 'Camera Accessories',
                        'children' => [
                            ['name' => 'Camera Lenses'],
                            ['name' => 'Tripods'],
                            ['name' => 'Memory Cards'],
                            ['name' => 'Camera Bags'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Networking',
                'children' => [
                    ['name' => 'Routers'],
                    ['name' => 'Network Switches'],
                    ['name' => 'Wi-Fi Extenders'],
                    ['name' => 'Network Adapters'],
                    ['name' => 'Network Cables'],
                ],
            ],
            [
                'name' => 'Smart Home',
                'children' => [
                    ['name' => 'Smart Lighting'],
                    ['name' => 'Security Cameras'],
                    ['name' => 'Smart Locks'],
                    ['name' => 'Smart Plugs'],
                    ['name' => 'Home Automation Hubs'],
                ],
            ],
            [
                'name' => 'Wearables',
                'children' => [
                    ['name' => 'Smartwatches'],
                    ['name' => 'Fitness Trackers'],
                    ['name' => 'VR Headsets'],
                ],
            ],
            [
                'name' => 'Office Technology',
                'children' => [
                    [
                        'name' => 'Printers and Scanners',
                        'children' => [
                            ['name' => 'Printers'],
                            ['name' => 'Scanners'],
                            ['name' => 'Ink and Toner'],
                        ],
                    ],
                    [
                        'name' => 'Office Accessories',
                        'children' => [
                            ['name' => 'Uninterruptible Power Supplies'],
                            ['name' => 'Document Shredders'],
                            ['name' => 'Label Printers'],
                        ],
                    ],
                ],
            ],
        ];

        $this->createCategories($categories);
    }

    private function createCategories(
        array $categories,
        ?Category $parent = null,
    ): void {
        foreach ($categories as $index => $data) {
            $category = Category::query()->updateOrCreate(
                [
                    'slug' => Str::slug($data['name']),
                ],
                [
                    'parent_id' => $parent?->id,
                    'name' => $data['name'],
                    'description' => 'Browse '.$data['name'].' and related technology products.',
                    'is_active' => true,
                    'sort_order' => ($index + 1) * 10,
                ],
            );

            $this->createCategories(
                $data['children'] ?? [],
                $category,
            );
        }
    }
}