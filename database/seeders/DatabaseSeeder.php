<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(DepartamentoSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(DistritoSeeder::class);
        // $this->call(UsuarioSeeder::class);
        // $this->call(RecetaSeeder::class);
        // $this->call(DiagnosticoSeeder::class);
        $this->call(EstadoSolicitudSeeder::class);
        // $this->call(AnuncioSeeder::class);
        // \App\Models\Anuncio::factory()->count(20)->create();
        // $this->call(SolicitudSeeder::class);
    }
}
