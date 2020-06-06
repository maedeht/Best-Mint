<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends Base
{
    public $content;
    public $header;

    public function testGettingFileRealPath()
    {
        $file = $this->createFakeFile();
        $this->assertEquals($file->getRealPath(),
		$this->productService->getFileRealPath($file));
    }

    public function testTurningFileIntoArray()
    {
        $file = $this->createFakeFile();
        $this->assertEquals(
		count(explode("\n", $this->content)),
		count($this->productService->turnFileIntoArray($file))
	);
    }

    public function testGettingProductColumns()
    {
        $file = $this->createFakeFile();
        $columns = str_replace(["\r", "\n"], '', $this->content[0]);
        $this->assertEquals(
		explode(',', $columns),
		$this->productService->getProductsColumns($this->content)
	);
    }

    private function createFakeFile()
    {
       Storage::fake('uploads');
       $header = 'category,title,price,description,qty';
       $row1 = 'glasses,lavaei,220000,ﺍﻮﻫ چﻩ ﺵیکﻩ,27';
       $row2 = 'shirts,hiliko,80000,ﺍیﻦﻣ ﺏگیﺭ ﺩیگﻩ,90';

       $this->content = implode("\n", [$header, $row1, $row2]);

       return UploadedFile::fake()->createWithContent(
                         'fake.csv',
                         $this->content
                      );
    }
}
