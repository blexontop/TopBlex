<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $estructura = [
            'hombre' => [
                'name' => 'Hombre',
                'tipos' => [
                    'chandal' => 'Chandal',
                    'vaqueros' => 'Vaqueros',
                    'zapatos' => 'Zapatos',
                    'accesorios' => 'Accesorios',
                ],
            ],
            'mujer' => [
                'name' => 'Mujer',
                'tipos' => [
                    'chandal' => 'Chandal',
                    'vaqueros' => 'Vaqueros',
                    'zapatos' => 'Zapatos',
                    'accesorios' => 'Accesorios',
                ],
            ],
        ];

        $categories = [];

        foreach ($estructura as $generoSlug => $generoData) {
            $genero = Category::updateOrCreate(
                ['slug' => $generoSlug],
                [
                    'name' => $generoData['name'],
                    'description' => 'Category principal de ' . strtolower($generoData['name']),
                    'parent_id' => null,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            foreach ($generoData['tipos'] as $tipoSlug => $tipoNombre) {
                $slugCompleto = $generoSlug . '-' . $tipoSlug;

                $categoria = Category::updateOrCreate(
                    ['slug' => $slugCompleto],
                    [
                        'name' => $tipoNombre,
                        'description' => $tipoNombre . ' para ' . strtolower($generoData['name']),
                        'parent_id' => $genero->id,
                        'is_active' => true,
                        'sort_order' => 1,
                    ]
                );

                $categories[$generoSlug . ':' . $tipoSlug] = $categoria->id;
            }
        }

        $products = [
            ['name' => 'Chandal Urban Hombre', 'genero' => 'hombre', 'tipo' => 'chandal', 'price' => 79.90, 'stock' => 24, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Chandal Tech Hombre', 'genero' => 'hombre', 'tipo' => 'chandal', 'price' => 89.90, 'stock' => 18, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Vaqueros Slim Hombre', 'genero' => 'hombre', 'tipo' => 'vaqueros', 'price' => 59.90, 'stock' => 30, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Vaqueros Relax Hombre', 'genero' => 'hombre', 'tipo' => 'vaqueros', 'price' => 64.90, 'stock' => 22, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1604176424472-9d2f9f3f94a5?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Zapatos Runner Hombre', 'genero' => 'hombre', 'tipo' => 'zapatos', 'price' => 99.90, 'stock' => 16, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Zapatos Casual Hombre', 'genero' => 'hombre', 'tipo' => 'zapatos', 'price' => 84.90, 'stock' => 21, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Accesorio Gorra Hombre', 'genero' => 'hombre', 'tipo' => 'accesorios', 'price' => 24.90, 'stock' => 35, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Accesorio Mochila Hombre', 'genero' => 'hombre', 'tipo' => 'accesorios', 'price' => 49.90, 'stock' => 14, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'],

            ['name' => 'Chandal Urban Mujer', 'genero' => 'mujer', 'tipo' => 'chandal', 'price' => 76.90, 'stock' => 26, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Chandal Soft Mujer', 'genero' => 'mujer', 'tipo' => 'chandal', 'price' => 82.90, 'stock' => 19, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Vaqueros Skinny Mujer', 'genero' => 'mujer', 'tipo' => 'vaqueros', 'price' => 62.90, 'stock' => 28, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Vaqueros Wide Mujer', 'genero' => 'mujer', 'tipo' => 'vaqueros', 'price' => 66.90, 'stock' => 20, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1551232864-3f0890e580d9?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Zapatos Runner Mujer', 'genero' => 'mujer', 'tipo' => 'zapatos', 'price' => 94.90, 'stock' => 17, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1463100099107-aa0980c362e6?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Zapatos Casual Mujer', 'genero' => 'mujer', 'tipo' => 'zapatos', 'price' => 88.90, 'stock' => 15, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Accesorio Bolso Mujer', 'genero' => 'mujer', 'tipo' => 'accesorios', 'price' => 54.90, 'stock' => 13, 'is_featured' => true, 'imagen' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Accesorio Cinturon Mujer', 'genero' => 'mujer', 'tipo' => 'accesorios', 'price' => 22.90, 'stock' => 27, 'is_featured' => false, 'imagen' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=1200&q=80'],
        ];

        foreach ($products as $item) {
            $sku = 'TBX-' . Str::upper(Str::slug($item['name'], '-'));
            $categoriaId = $categories[$item['genero'] . ':' . $item['tipo']] ?? null;

            if (!$categoriaId) {
                continue;
            }

            $producto = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => 'Product premium de ' . $item['tipo'] . ' para ' . $item['genero'] . '.',
                    'short_description' => $item['name'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'is_visible' => true,
                    'is_featured' => $item['is_featured'],
                    'category_id' => $categoriaId,
                    'collection_id' => null,
                ]
            );

            DB::table('product_images')->updateOrInsert(
                [
                    'product_id' => $producto->id,
                    'sort_order' => 0,
                ],
                [
                    'url' => $item['imagen'],
                    'alt' => $item['name'],
                    'es_principal' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
