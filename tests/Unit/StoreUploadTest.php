<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreUploadTest extends TestCase
{
    protected function tearDown(): void
    {
        if (is_dir(public_path('uploads/unitwebp'))) {
            \Illuminate\Support\Facades\File::deleteDirectory(public_path('uploads/unitwebp'));
        }
        parent::tearDown();
    }

    #[Test]
    public function it_converts_uploaded_image_to_webp()
    {
        $file = UploadedFile::fake()->image('foto.png', 200, 150);

        $stored = store_upload($file, 'unitwebp');

        $this->assertSame('webp', pathinfo($stored, PATHINFO_EXTENSION));
        $this->assertFileExists(public_path('uploads/' . $stored));

        $header = file_get_contents(public_path('uploads/' . $stored), false, null, 0, 4);
        $this->assertSame('RIFF', $header);
    }

    #[Test]
    public function it_passes_non_image_files_through_unchanged()
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 10);

        $stored = store_upload($file, 'unitwebp');

        $this->assertSame('pdf', pathinfo($stored, PATHINFO_EXTENSION));
        $this->assertFileExists(public_path('uploads/' . $stored));
    }
}
