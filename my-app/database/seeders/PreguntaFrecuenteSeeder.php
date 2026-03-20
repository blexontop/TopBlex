<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PreguntaFrecuenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'pregunta' => 'Cuanto tarda el envio?',
                'respuesta' => 'Los pedidos nacionales tardan entre 24 y 72 horas laborables segun la ciudad.',
                'orden' => 1,
            ],
            [
                'pregunta' => 'Puedo devolver un producto?',
                'respuesta' => 'Si, tienes 30 dias desde la recepcion para solicitar devolucion si el producto esta en buen estado.',
                'orden' => 2,
            ],
            [
                'pregunta' => 'Como se calcula la talla?',
                'respuesta' => 'En cada producto tienes una guia de talla recomendada. Si dudas, contactanos y te asesoramos.',
                'orden' => 3,
            ],
            [
                'pregunta' => 'Que metodos de pago aceptan?',
                'respuesta' => 'Aceptamos tarjeta, transferencia y metodos digitales disponibles en el checkout.',
                'orden' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('pregunta_frecuentes')->updateOrInsert(
                ['pregunta' => $faq['pregunta']],
                [
                    'respuesta' => $faq['respuesta'],
                    'orden' => $faq['orden'],
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
