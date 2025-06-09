<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'name' => 'Vehículos',
                'description' => 'Compra y venta de vehículos nuevos y usados, repuestos y accesorios automotrices.',
                'children' => [
                    [
                        'name' => 'Autos',
                        'description' => 'Automóviles de todas las marcas y modelos'
                    ],
                    [
                        'name' => 'Motos',
                        'description' => 'Motocicletas, scooters y accesorios'
                    ],
                    [
                        'name' => 'Camiones',
                        'description' => 'Vehículos de carga y transporte pesado'
                    ],
                    [
                        'name' => 'Buses y Microbuses',
                        'description' => 'Transporte colectivo y vehículos de pasajeros'
                    ],
                    [
                        'name' => 'Accesorios para Vehículos',
                        'description' => 'Repuestos, llantas, sistemas de audio y más'
                    ],
                    [
                        'name' => 'Repuestos',
                        'description' => 'Partes y piezas para todo tipo de vehículos'
                    ],
                    [
                        'name' => 'Otros Vehículos',
                        'description' => 'Vehículos especiales y de uso particular'
                    ],
                    [
                        'name' => 'Embarcaciones',
                        'description' => 'Botes, lanchas y yates'
                    ],
                ]
            ],
            [
                'name' => 'Inmuebles',
                'description' => 'Propiedades en venta, alquiler y bienes raíces comerciales.',
                'children' => [
                    [
                        'name' => 'Alquiler',
                        'description' => 'Propiedades residenciales en alquiler'
                    ],
                    [
                        'name' => 'Anticresis',
                        'description' => 'Opciones de anticresis para viviendas'
                    ],
                    [
                        'name' => 'Venta',
                        'description' => 'Propiedades residenciales en venta'
                    ],
                    [
                        'name' => 'Alquiler Vacacional',
                        'description' => 'Renta temporal para vacaciones'
                    ],
                    [
                        'name' => 'Bienes Raíces Comerciales',
                        'description' => 'Locales, oficinas y propiedades comerciales'
                    ],
                    [
                        'name' => 'Terrenos',
                        'description' => 'Lotes y parcelas disponibles'
                    ],
                ]
            ],
            [
                'name' => 'Empleos',
                'description' => 'Ofertas de trabajo y oportunidades laborales.',
                'children' => [
                    [
                        'name' => 'Ofertas de Empleo',
                        'description' => 'Vacantes disponibles en diferentes sectores'
                    ],
                    [
                        'name' => 'Busco Trabajo',
                        'description' => 'Perfiles profesionales en busca de empleo'
                    ],
                    [
                        'name' => 'Servicios Profesionales',
                        'description' => 'Servicios ofrecidos por profesionales independientes'
                    ],
                ]
            ],
            [
                'name' => 'Negocios y Oportunidades',
                'description' => 'Oportunidades de negocio, franquicias y equipamiento comercial.',
                'children' => [
                    [
                        'name' => 'Negocios en Venta',
                        'description' => 'Empresas y negocios establecidos en venta'
                    ],
                    [
                        'name' => 'Franquicias',
                        'description' => 'Oportunidades de franquicias disponibles'
                    ],
                    [
                        'name' => 'Oportunidades de Negocio',
                        'description' => 'Ideas y oportunidades de negocio'
                    ],
                    [
                        'name' => 'Inversiones',
                        'description' => 'Oportunidades de inversión y socios comerciales'
                    ],
                    [
                        'name' => 'Materia Prima',
                        'description' => 'Materiales para producción e industria'
                    ],
                    [
                        'name' => 'Equipamiento para Negocios',
                        'description' => 'Mobiliario y equipo comercial'
                    ],
                ]
            ],
            [
                'name' => 'Servicios',
                'description' => 'Servicios profesionales, técnicos y personales.',
                'children' => [
                    [
                        'name' => 'Servicios Profesionales',
                        'description' => 'Asesoría legal, contable y profesional',
                        'slug' => 'servicios-profesionales-2' // Slug único
                    ],
                    [
                        'name' => 'Reparaciones y Mantenimiento',
                        'description' => 'Servicios técnicos y de reparación'
                    ],
                    [
                        'name' => 'Cuidado Personal',
                        'description' => 'Belleza, salud y cuidado personal'
                    ],
                    [
                        'name' => 'Clases y Cursos',
                        'description' => 'Educación, capacitación y tutorías'
                    ],
                    [
                        'name' => 'Eventos y Fiestas',
                        'description' => 'Organización de eventos y celebraciones'
                    ],
                    [
                        'name' => 'Transporte',
                        'description' => 'Servicios de transporte de personas y carga'
                    ],
                    [
                        'name' => 'Tecnología y Diseño',
                        'description' => 'Servicios digitales, diseño y desarrollo'
                    ],
                    [
                        'name' => 'Otros Servicios',
                        'description' => 'Diversos servicios disponibles'
                    ],
                ]
            ],
            [
                'name' => 'Comunidad',
                'description' => 'Eventos locales, grupos y actividades comunitarias.',
                'children' => [
                    [
                        'name' => 'Eventos',
                        'description' => 'Actividades y eventos locales'
                    ],
                    [
                        'name' => 'Voluntariado',
                        'description' => 'Oportunidades para servir a la comunidad'
                    ],
                    [
                        'name' => 'Perdidos y Encontrados',
                        'description' => 'Objetos y mascotas perdidas'
                    ],
                ]
            ],
            [
                'name' => 'Hogar y Jardín',
                'description' => 'Muebles, electrodomésticos, decoración y artículos para el hogar.',
                'children' => [
                    [
                        'name' => 'Muebles',
                        'description' => 'Mobiliario para el hogar y oficina'
                    ],
                    [
                        'name' => 'Electrodomésticos',
                        'description' => 'Aparatos eléctricos para el hogar'
                    ],
                    [
                        'name' => 'Decoración',
                        'description' => 'Artículos decorativos para el hogar'
                    ],
                    [
                        'name' => 'Jardín',
                        'description' => 'Plantas, herramientas y artículos de jardinería'
                    ],
                    [
                        'name' => 'Construcción',
                        'description' => 'Materiales y servicios de construcción'
                    ],
                    [
                        'name' => 'Herramientas',
                        'description' => 'Herramientas manuales y eléctricas'
                    ],
                ]
            ],
            [
                'name' => 'Electrónica y Computación',
                'description' => 'Dispositivos electrónicos, computadoras y accesorios tecnológicos.',
                'children' => [
                    [
                        'name' => 'Computadoras',
                        'description' => 'Equipos de escritorio y componentes'
                    ],
                    [
                        'name' => 'Laptops',
                        'description' => 'Computadoras portátiles y notebooks'
                    ],
                    [
                        'name' => 'Tablets',
                        'description' => 'Dispositivos tablet y accesorios'
                    ],
                    [
                        'name' => 'Celulares',
                        'description' => 'Teléfonos móviles y smartphones'
                    ],
                    [
                        'name' => 'Audio y Video',
                        'description' => 'Equipos de sonido y televisores'
                    ],
                    [
                        'name' => 'Videojuegos',
                        'description' => 'Consolas, juegos y accesorios'
                    ],
                    [
                        'name' => 'Accesorios',
                        'description' => 'Periféricos y complementos tecnológicos'
                    ],
                ]
            ],
            [
                'name' => 'Ocio y Deportes',
                'description' => 'Artículos deportivos, pasatiempos y actividades recreativas.',
                'children' => [
                    [
                        'name' => 'Arte y Antigüedades',
                        'description' => 'Obras de arte y objetos coleccionables'
                    ],
                    [
                        'name' => 'Música e Instrumentos',
                        'description' => 'Instrumentos musicales y equipos de audio'
                    ],
                    [
                        'name' => 'Deportes',
                        'description' => 'Equipamiento deportivo y fitness'
                    ],
                    [
                        'name' => 'Bicicletas',
                        'description' => 'Bicicletas y accesorios para ciclismo'
                    ],
                    [
                        'name' => 'Libros y Revistas',
                        'description' => 'Material de lectura y publicaciones'
                    ],
                    [
                        'name' => 'Pasatiempos',
                        'description' => 'Hobbies y actividades recreativas'
                    ],
                ]
            ],
            [
                'name' => 'Moda y Belleza',
                'description' => 'Ropa, calzado, accesorios y productos de belleza.',
                'children' => [
                    [
                        'name' => 'Ropa',
                        'description' => 'Prendas de vestir para todas las edades'
                    ],
                    [
                        'name' => 'Zapatos',
                        'description' => 'Calzado para diferentes ocasiones'
                    ],
                    [
                        'name' => 'Accesorios',
                        'description' => 'Complementos de moda y bolsos'
                    ],
                    [
                        'name' => 'Belleza',
                        'description' => 'Productos de cuidado personal y cosméticos'
                    ],
                    [
                        'name' => 'Relojes y Joyería',
                        'description' => 'Joyas y accesorios de lujo'
                    ],
                ]
            ],
            [
                'name' => 'Fiestas y Eventos',
                'description' => 'Artículos para celebraciones y servicios de organización de eventos.',
                'children' => [
                    [
                        'name' => 'Artículos para Fiestas',
                        'description' => 'Decoración y suministros para fiestas'
                    ],
                    [
                        'name' => 'Organización de Eventos',
                        'description' => 'Servicios profesionales para eventos'
                    ],
                ]
            ],
            [
                'name' => 'Mascotas',
                'description' => 'Animales domésticos, productos y servicios para mascotas.',
                'children' => [
                    [
                        'name' => 'Mascotas',
                        'description' => 'Animales domésticos en adopción o venta'
                    ],
                    [
                        'name' => 'Productos para Mascotas',
                        'description' => 'Alimentos, accesorios y cuidados'
                    ],
                    [
                        'name' => 'Servicios para Mascotas',
                        'description' => 'Veterinarios, peluquería y cuidados'
                    ],
                ]
            ],
            [
                'name' => 'Otros',
                'description' => 'Diversos artículos y categorías especiales.',
                'children' => [
                    [
                        'name' => 'Varios',
                        'description' => 'Artículos diversos y generales'
                    ],
                    [
                        'name' => 'Donaciones',
                        'description' => 'Artículos donados y causas benéficas'
                    ],
                    [
                        'name' => 'Coleccionables',
                        'description' => 'Objetos de colección y rarezas'
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            $parent = Category::create([
                'name' => $category['name'],
                'slug' => $this->generateUniqueSlug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);

            foreach ($category['children'] as $child) {
                // Usar slug personalizado si existe, de lo contrario generar uno único
                $slug = $child['slug'] ?? $this->generateUniqueSlug($child['name']);
                
                $parent->children()->create([
                    'name' => $child['name'],
                    'slug' => $slug,
                    'description' => $child['description'],
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Genera un slug único comprobando su existencia en la base de datos
     */
    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}