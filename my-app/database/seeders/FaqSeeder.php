<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'pregunta' => 'Cuanto tarda el envio?',
                'respuesta' => 'Los orders nacionales tardan entre 24 y 72 horas laborables segun la city.',
                'sort_order' => 1,
            ],
            [
                'pregunta' => 'Puedo devolver un producto?',
                'respuesta' => 'Si, tienes 30 dias desde la recepcion para solicitar devolucion si el producto esta en buen status.',
                'sort_order' => 2,
            ],
            [
                'pregunta' => 'Como se calcula la talla?',
                'respuesta' => 'En cada producto tienes una guia de talla recomendada. Si dudas, contactanos y te asesoramos.',
                'sort_order' => 3,
            ],
            [
                'pregunta' => 'Que methods de pago aceptan?',
                'respuesta' => 'Aceptamos card, transferencia y methods digitales disponibles en el checkout.',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->updateOrInsert(
                ['pregunta' => $faq['pregunta']],
                [
                    'respuesta' => $faq['respuesta'],
                    'sort_order' => $faq['sort_order'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
