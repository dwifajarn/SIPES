<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    #[Test]
    public function halaman_landing_page_bisa_diakses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sistem Pendataan Peserta');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar Akun');
    }
}
