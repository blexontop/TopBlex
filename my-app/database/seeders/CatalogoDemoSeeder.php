<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $estructura = [
            'hombre' => [
                'nombre' => 'Hombre',
                'tipos' => [
                    'chandal' => 'Chandal',
                    'vaqueros' => 'Vaqueros',
                    'zapatos' => 'Zapatos',
                    'accesorios' => 'Accesorios',
                ],
            ],
            'mujer' => [
                'nombre' => 'Mujer',
                'tipos' => [
                    'chandal' => 'Chandal',
                    'vaqueros' => 'Vaqueros',
                    'zapatos' => 'Zapatos',
                    'accesorios' => 'Accesorios',
                ],
            ],
        ];

        $categorias = [];

        foreach ($estructura as $generoSlug => $generoData) {
            $genero = Categoria::updateOrCreate(
                ['slug' => $generoSlug],
                [
                    'nombre' => $generoData['nombre'],
                    'descripcion' => 'Categoria principal de ' . strtolower($generoData['nombre']),
                    'parent_id' => null,
                    'activo' => true,
                    'orden' => 0,
                ]
            );

            foreach ($generoData['tipos'] as $tipoSlug => $tipoNombre) {
                $slugCompleto = $generoSlug . '-' . $tipoSlug;

                $categoria = Categoria::updateOrCreate(
                    ['slug' => $slugCompleto],
                    [
                        'nombre' => $tipoNombre,
                        'descripcion' => $tipoNombre . ' para ' . strtolower($generoData['nombre']),
                        'parent_id' => $genero->id,
                        'activo' => true,
                        'orden' => 1,
                    ]
                );

                $categorias[$generoSlug . ':' . $tipoSlug] = $categoria->id;
            }
        }

        $productos = [
            ['nombre' => 'Chandal Urban Hombre', 'genero' => 'hombre', 'tipo' => 'chandal', 'precio' => 79.90, 'stock' => 24, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Chandal Tech Hombre', 'genero' => 'hombre', 'tipo' => 'chandal', 'precio' => 89.90, 'stock' => 18, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Vaqueros Slim Hombre', 'genero' => 'hombre', 'tipo' => 'vaqueros', 'precio' => 59.90, 'stock' => 30, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Vaqueros Relax Hombre', 'genero' => 'hombre', 'tipo' => 'vaqueros', 'precio' => 64.90, 'stock' => 22, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1604176424472-9d2f9f3f94a5?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Zapatos Runner Hombre', 'genero' => 'hombre', 'tipo' => 'zapatos', 'precio' => 99.90, 'stock' => 16, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Zapatos Casual Hombre', 'genero' => 'hombre', 'tipo' => 'zapatos', 'precio' => 84.90, 'stock' => 21, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Accesorio Gorra Hombre', 'genero' => 'hombre', 'tipo' => 'accesorios', 'precio' => 24.90, 'stock' => 35, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Accesorio Mochila Hombre', 'genero' => 'hombre', 'tipo' => 'accesorios', 'precio' => 49.90, 'stock' => 14, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'],

            ['nombre' => 'Chandal Urban Mujer', 'genero' => 'mujer', 'tipo' => 'chandal', 'precio' => 76.90, 'stock' => 26, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Chandal Soft Mujer', 'genero' => 'mujer', 'tipo' => 'chandal', 'precio' => 82.90, 'stock' => 19, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Vaqueros Skinny Mujer', 'genero' => 'mujer', 'tipo' => 'vaqueros', 'precio' => 62.90, 'stock' => 28, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Vaqueros Wide Mujer', 'genero' => 'mujer', 'tipo' => 'vaqueros', 'precio' => 66.90, 'stock' => 20, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1551232864-3f0890e580d9?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Zapatos Runner Mujer', 'genero' => 'mujer', 'tipo' => 'zapatos', 'precio' => 94.90, 'stock' => 17, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1463100099107-aa0980c362e6?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Zapatos Casual Mujer', 'genero' => 'mujer', 'tipo' => 'zapatos', 'precio' => 88.90, 'stock' => 15, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Accesorio Bolso Mujer', 'genero' => 'mujer', 'tipo' => 'accesorios', 'precio' => 54.90, 'stock' => 13, 'destacado' => true, 'imagen' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=1200&q=80'],
            ['nombre' => 'Accesorio Cinturon Mujer', 'genero' => 'mujer', 'tipo' => 'accesorios', 'precio' => 22.90, 'stock' => 27, 'destacado' => false, 'imagen' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=1200&q=80'],
        ];

        foreach ($productos as $item) {
            $sku = 'TBX-' . Str::upper(Str::slug($item['nombre'], '-'));
            $categoriaId = $categorias[$item['genero'] . ':' . $item['tipo']] ?? null;

            if (!$categoriaId) {
                continue;
            }

            $producto = Producto::updateOrCreate(
                ['sku' => $sku],
                [
                    'nombre' => $item['nombre'],
                    'slug' => Str::slug($item['nombre']),
                    'descripcion' => 'Producto premium de ' . $item['tipo'] . ' para ' . $item['genero'] . '.',
                    'descripcion_corta' => $item['nombre'],
                    'precio' => $item['precio'],
                    'stock' => $item['stock'],
                    'visible' => true,
                    'destacado' => $item['destacado'],
                    'categoria_id' => $categoriaId,
                    'coleccion_id' => null,
                ]
            );

            DB::table('imagen_productos')->updateOrInsert(
                [
                    'producto_id' => $producto->id,
                    'orden' => 0,
                ],
                [
                    'url' => $item['imagen'],
                    'alt' => $item['nombre'],
                    'es_principal' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
