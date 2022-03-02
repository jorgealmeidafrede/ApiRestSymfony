<?php

namespace App\Tests\Service;

use League\Flysystem\FilesystemOperator;
use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class FileUploaderUnitTest extends TestCase
{
    public function testSuccess()
    {
        $base64Image = "data:image/jpeg;base64,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";
        $data = explode(",", $base64Image);
        /**
         * @var FilesystemOperator&MockObject
         */

        $filesystem = $this->getMockBuilder(FilesystemOperator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $filesystem
            ->expects(self::exactly(1))
            ->method('write')
            ->with( $this->isType('string') , base64_decode($data[1]) );

        $fileUploader = new FileUploader($filesystem);
        $filename = $fileUploader->uploadBase64File($base64Image);
        $this->assertNotEmpty($filename);
        $this->assertStringContainsString('.jpeg', $filename);
    }
}