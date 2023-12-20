<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


class KategoriBarangTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        // $this->assertTrue(true);
        $value = [
            'nama_kategori' => $this->faker->word,
            'diskripsi_kategori' => $this->faker->setence
        ];

        $this->post('/admin/kategoribarang', $value)->assertStatus(201)->assertJson($value);
    }
}