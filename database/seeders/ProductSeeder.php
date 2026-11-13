<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(20261113);

        $imagePaths = $this->storeSeedImages();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($categories->isEmpty()) {
            throw new RuntimeException('Run CategorySeeder before ProductSeeder.');
        }

        foreach ($categories as $category) {
            $this->createProducts(
                $category,
                $imagePaths,
            );
        }
    }

    private function createProducts(
        Category $category,
        array $imagePaths,
    ): void {
        $profile = $this->profileFor($category->name);
        $productType = Str::singular($category->name);
        $modelPrefix = Str::upper(
            Str::substr(
                str_replace('-', '', Str::slug($category->name)),
                0,
                4,
            ),
        );

        for ($index = 1; $index <= 10; $index++) {
            $brand = $profile['brands'][
                ($index - 1) % count($profile['brands'])
            ];

            $model = $modelPrefix.'-'.str_pad(
                (string) ($index * 10),
                3,
                '0',
                STR_PAD_LEFT,
            );

            $name = "{$brand} {$productType} {$model}";
            $price = fake()->randomFloat(
                2,
                $profile['minimum_price'],
                $profile['maximum_price'],
            );

            $discountPercentage = $index % 3 === 0
                ? fake()->numberBetween(10, 30)
                : null;

            $salePrice = $discountPercentage === null
                ? null
                : round(
                    $price * ((100 - $discountPercentage) / 100),
                    2,
                );

            $product = Product::query()->updateOrCreate(
                [
                    'slug' => Str::slug(
                        $name.'-'.$category->id,
                    ),
                ],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'sku' => 'KS-'.$category->id.'-'.str_pad(
                        (string) $index,
                        3,
                        '0',
                        STR_PAD_LEFT,
                    ),
                    'description' => "{$name} is a realistic test product in the {$category->name} category, prepared for storefront, cart, inventory, and checkout development.",
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'status' => ProductStatus::ACTIVE->value,
                    'product_type' => ProductType::PHYSICAL->value,
                    'stock_quantity' => $index === 10
                        ? 0
                        : fake()->numberBetween(5, 100),
                    'track_inventory' => true,
                ],
            );

            $this->attachImages(
                $product,
                $imagePaths,
            );
        }
    }

    private function storeSeedImages(): array
    {
        $sourceFiles = [
            'laptop.jpg',
            'laptop_2.jpg',
            'laptop_3.jpg',
        ];

        $storedPaths = [];

        foreach ($sourceFiles as $sourceFile) {
            $sourcePath = base_path($sourceFile);

            if (! File::exists($sourcePath)) {
                throw new RuntimeException(
                    "Missing seed image: {$sourceFile}",
                );
            }

            $storedPath = 'products/seeded/'.$sourceFile;

            $stored = Storage::disk('public')->put(
                $storedPath,
                File::get($sourcePath),
            );

            if (! $stored) {
                throw new RuntimeException(
                    "Could not store seed image: {$sourceFile}",
                );
            }

            $storedPaths[] = $storedPath;
        }

        return $storedPaths;
    }

    private function attachImages(
        Product $product,
        array $imagePaths,
    ): void {
        $product->productImages()->update([
            'is_primary' => false,
        ]);

        $primaryImageIndex = random_int(
            0,
            count($imagePaths) - 1,
        );

        foreach ($imagePaths as $index => $imagePath) {
            $product->productImages()->updateOrCreate(
                [
                    'image_path' => $imagePath,
                ],
                [
                    'is_primary' => $index === $primaryImageIndex,
                    'sort_order' => $index,
                ],
            );
        }
    }

    private function profileFor(string $categoryName): array
    {
        $name = Str::lower($categoryName);

        return match (true) {
            Str::contains($name, [
                'phone cases',
                'chargers',
                'power banks',
                'screen protectors',
            ]) => [
                'brands' => ['Anker', 'Belkin', 'Spigen', 'Baseus', 'UGREEN'],
                'minimum_price' => 10,
                'maximum_price' => 150,
            ],

            Str::contains($name, [
                'laptop bags',
                'camera bags',
                'tripods',
            ]) => [
                'brands' => ['Targus', 'Manfrotto', 'Lowepro', 'Peak Design'],
                'minimum_price' => 25,
                'maximum_price' => 400,
            ],

            Str::contains($name, [
                'laptop',
                'ultrabook',
            ]) => [
                'brands' => ['ASUS', 'Lenovo', 'Dell', 'HP', 'Acer', 'MSI'],
                'minimum_price' => 650,
                'maximum_price' => 2800,
            ],

            Str::contains($name, [
                'desktop',
                'workstation',
                'all-in-one',
            ]) => [
                'brands' => ['Dell', 'HP', 'Lenovo', 'ASUS', 'MSI'],
                'minimum_price' => 700,
                'maximum_price' => 4500,
            ],

            Str::contains($name, ['processor']) => [
                'brands' => ['Intel', 'AMD'],
                'minimum_price' => 100,
                'maximum_price' => 900,
            ],

            Str::contains($name, ['graphics card']) => [
                'brands' => ['ASUS', 'MSI', 'Gigabyte', 'Sapphire', 'Zotac'],
                'minimum_price' => 250,
                'maximum_price' => 2200,
            ],

            Str::contains($name, ['motherboard']) => [
                'brands' => ['ASUS', 'MSI', 'Gigabyte', 'ASRock'],
                'minimum_price' => 100,
                'maximum_price' => 700,
            ],

            Str::contains($name, [
                'computer memory',
                'storage drive',
                'memory card',
            ]) => [
                'brands' => ['Samsung', 'Kingston', 'Crucial', 'SanDisk', 'Corsair'],
                'minimum_price' => 25,
                'maximum_price' => 600,
            ],

            Str::contains($name, [
                'power supplies',
                'computer cases',
                'cooling',
            ]) => [
                'brands' => ['Corsair', 'Cooler Master', 'NZXT', 'Thermaltake', 'Noctua'],
                'minimum_price' => 40,
                'maximum_price' => 450,
            ],

            Str::contains($name, [
                'keyboard',
                'mice',
                'mouse',
                'webcam',
                'controller',
            ]) => [
                'brands' => ['Logitech', 'Razer', 'Corsair', 'SteelSeries', 'Microsoft'],
                'minimum_price' => 20,
                'maximum_price' => 350,
            ],

            Str::contains($name, [
                'smartphone',
                'android phone',
                'iphone',
                'mobile devices',
            ]) => [
                'brands' => ['Apple', 'Samsung', 'Google', 'Xiaomi', 'OnePlus'],
                'minimum_price' => 250,
                'maximum_price' => 1800,
            ],

            Str::contains($name, [
                'tablet',
                'ipad',
            ]) => [
                'brands' => ['Apple', 'Samsung', 'Lenovo', 'Microsoft', 'Xiaomi'],
                'minimum_price' => 200,
                'maximum_price' => 1600,
            ],

            Str::contains($name, [
                'console',
                'playstation',
                'xbox',
                'nintendo',
            ]) => [
                'brands' => ['Sony', 'Microsoft', 'Nintendo'],
                'minimum_price' => 250,
                'maximum_price' => 800,
            ],

            Str::contains($name, [
                'video games',
                'pc games',
                'console games',
            ]) => [
                'brands' => ['EA', 'Ubisoft', 'Activision', 'Nintendo', 'PlayStation'],
                'minimum_price' => 25,
                'maximum_price' => 100,
            ],

            Str::contains($name, [
                'headphone',
                'earbud',
                'gaming headset',
            ]) => [
                'brands' => ['Sony', 'Bose', 'Sennheiser', 'JBL', 'Audio-Technica'],
                'minimum_price' => 30,
                'maximum_price' => 700,
            ],

            Str::contains($name, [
                'speaker',
                'soundbar',
            ]) => [
                'brands' => ['JBL', 'Sonos', 'Bose', 'Sony', 'Samsung'],
                'minimum_price' => 50,
                'maximum_price' => 1500,
            ],

            Str::contains($name, ['microphone']) => [
                'brands' => ['Shure', 'Rode', 'Blue', 'Audio-Technica'],
                'minimum_price' => 40,
                'maximum_price' => 700,
            ],

            Str::contains($name, [
                'television',
                'smart tv',
                'oled tv',
                'qled tv',
            ]) => [
                'brands' => ['Samsung', 'LG', 'Sony', 'TCL', 'Hisense'],
                'minimum_price' => 300,
                'maximum_price' => 5000,
            ],

            Str::contains($name, [
                'monitor',
                'displays',
            ]) => [
                'brands' => ['LG', 'Samsung', 'Dell', 'ASUS', 'BenQ'],
                'minimum_price' => 150,
                'maximum_price' => 1800,
            ],

            Str::contains($name, ['projector']) => [
                'brands' => ['Epson', 'BenQ', 'Optoma', 'ViewSonic'],
                'minimum_price' => 250,
                'maximum_price' => 3500,
            ],

            Str::contains($name, ['security cameras']) => [
                'brands' => ['Arlo', 'Ring', 'Eufy', 'Google Nest'],
                'minimum_price' => 50,
                'maximum_price' => 600,
            ],

            Str::contains($name, [
                'camera',
                'mirrorless',
                'dslr',
                'action cameras',
            ]) => [
                'brands' => ['Canon', 'Sony', 'Nikon', 'Fujifilm', 'GoPro'],
                'minimum_price' => 250,
                'maximum_price' => 5000,
            ],

            Str::contains($name, ['lens']) => [
                'brands' => ['Canon', 'Sony', 'Nikon', 'Sigma', 'Tamron'],
                'minimum_price' => 150,
                'maximum_price' => 3500,
            ],

            Str::contains($name, [
                'router',
                'switch',
                'wi-fi',
                'network adapter',
                'network cable',
                'networking',
            ]) => [
                'brands' => ['TP-Link', 'Netgear', 'ASUS', 'Ubiquiti', 'Cisco'],
                'minimum_price' => 20,
                'maximum_price' => 1200,
            ],

            Str::contains($name, [
                'smart lighting',
                'smart locks',
                'smart plugs',
                'automation hubs',
                'smart home',
            ]) => [
                'brands' => ['Philips Hue', 'Google Nest', 'Amazon', 'Eufy', 'TP-Link'],
                'minimum_price' => 20,
                'maximum_price' => 600,
            ],

            Str::contains($name, [
                'smartwatch',
                'fitness tracker',
            ]) => [
                'brands' => ['Apple', 'Samsung', 'Garmin', 'Fitbit', 'Huawei'],
                'minimum_price' => 50,
                'maximum_price' => 1200,
            ],

            Str::contains($name, ['vr headset']) => [
                'brands' => ['Meta', 'HTC', 'Sony', 'Valve'],
                'minimum_price' => 300,
                'maximum_price' => 1500,
            ],

            Str::contains($name, [
                'printer',
                'scanner',
                'ink and toner',
            ]) => [
                'brands' => ['HP', 'Canon', 'Epson', 'Brother', 'Xerox'],
                'minimum_price' => 20,
                'maximum_price' => 1800,
            ],

            Str::contains($name, [
                'power supplies',
                'shredder',
                'label printer',
                'office',
            ]) => [
                'brands' => ['APC', 'Fellowes', 'Brother', 'Dymo', 'CyberPower'],
                'minimum_price' => 50,
                'maximum_price' => 1200,
            ],

            default => [
                'brands' => ['Samsung', 'Sony', 'ASUS', 'Lenovo', 'Logitech'],
                'minimum_price' => 50,
                'maximum_price' => 1500,
            ],
        };
    }
}