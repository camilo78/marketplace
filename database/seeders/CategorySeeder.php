<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Definimos las categorías principales con sus subcategorías, basándonos en Encuentra24.
        $categories = [
            [
                'name'        => 'Vehículos',
                'description' => 'Anuncios de automóviles, motocicletas, camiones y más.',
                'children'    => [
                    [
                        'name'        => 'Autos',
                        'description' => 'Venta de autos particulares y usados.',
                    ],
                    [
                        'name'        => 'Camionetas y Pickups',
                        'description' => 'Anuncios de camionetas y pickups para el transporte.',
                    ],
                    [
                        'name'        => 'Motocicletas',
                        'description' => 'Motocicletas de todas las marcas y modelos.',
                    ],
                    [
                        'name'        => 'Camiones',
                        'description' => 'Anuncios de camiones y vehículos de carga.',
                    ],
                ],
            ],
            [
                'name'        => 'Bienes Raíces',
                'description' => 'Propiedades en venta o alquiler: casas, apartamentos, terrenos, etc.',
                'children'    => [
                    [
                        'name'        => 'Casas',
                        'description' => 'Venta y alquiler de casas y residencias.',
                    ],
                    [
                        'name'        => 'Apartamentos',
                        'description' => 'Ofertas de apartamentos en diversas ubicaciones.',
                    ],
                    [
                        'name'        => 'Terrenos',
                        'description' => 'Terrenos para inversión y desarrollo.',
                    ],
                ],
            ],
            [
                'name'        => 'Empleos',
                'description' => 'Ofertas laborales y oportunidades de empleo.',
                'children'    => [
                    [
                        'name'        => 'Tiempo Completo',
                        'description' => 'Empleos a tiempo completo en diversas industrias.',
                    ],
                    [
                        'name'        => 'Medio Tiempo',
                        'description' => 'Oportunidades laborales a medio tiempo.',
                    ],
                    [
                        'name'        => 'Freelance',
                        'description' => 'Trabajos freelance y proyectos temporales.',
                    ],
                ],
            ],
            [
                'name'        => 'Servicios',
                'description' => 'Servicios profesionales ofrecidos por particulares o empresas.',
                'children'    => [
                    [
                        'name'        => 'Servicios Profesionales',
                        'description' => 'Consultorías, asesorías y otros servicios especializados.',
                    ],
                    [
                        'name'        => 'Servicios para el Hogar',
                        'description' => 'Reparaciones, limpieza y mantenimiento del hogar.',
                    ],
                    [
                        'name'        => 'Servicios de Salud',
                        'description' => 'Clínicas, atención médica y otros servicios relacionados.',
                    ],
                ],
            ],
        ];

        // Recorrer las categorías principales y crear la estructura usando Nested Set.
        foreach ($categories as $catData) {
            // Capturamos los posibles hijos.
            $children = $catData['children'] ?? [];
            unset($catData['children']);

            // Añadimos el slug y el estado de forma predeterminada.
            $catData['slug'] = Str::slug($catData['name']);
            $catData['is_active'] = true;

            // Creamos el nodo raíz (categoría principal).
            $parent = Category::create($catData);

            // Creamos cada hijo y lo asociamos al padre.
            foreach ($children as $childData) {
                $childData['slug'] = Str::slug($childData['name']);
                $childData['is_active'] = true;

                // Creamos el registro del hijo.
                $child = Category::create($childData);

                // Insertamos el hijo en el árbol (debajo del padre).
                $child->appendToNode($parent)->save();
            }
        }
    }
}