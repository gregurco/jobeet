<?php

namespace App\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends TestCase
{
    public function testCreateCategory(): void
    {
        /** @var UploadedFile|MockObject $file */
        $file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file->expects($this->once())
            ->method('guessExtension')
            ->willReturn('_EXT_');

        $file->expects($this->once())
            ->method('move')
            ->with(
                $this->equalTo('_DIR_'),
                $this->stringEndsWith('._EXT_')
            );

        $uploader = new FileUploader('_DIR_');
        $fileName = $uploader->upload($file);

        $this->assertRegExp('/.+?\._EXT_/', $fileName);
    }
}
