<?php

namespace Rakit\Validation\Tests;

use Rakit\Validation\Rules\UploadedFileType;
use PHPUnit\Framework\TestCase;

class UploadedFileTypeTest extends TestCase
{

    public function setUp()
    {
        $this->rule = new UploadedFileType();
    }

    public function testFileTypes()
    {

        $rule = $this->getMockBuilder(UploadedFileType::class)
            ->setMethods(['isUploadedFile'])
            ->getMock();

        $rule->expects($this->exactly(0))
            ->method('isUploadedFile')
            ->willReturn(true);

        $rule->fileTypes('png|jpeg');

        $this->assertFalse($rule->check([
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'text/plain',
            'size' => 1024, // 1K
            'tmp_name' => __FILE__,
            'error' => 0
        ]));

        $this->assertTrue($rule->check([
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'image/png',
            'size' => 10 * 1024,
            'tmp_name' => __FILE__,
            'error' => 0
        ]));

        $this->assertTrue($rule->check([
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'image/jpeg',
            'size' => 10 * 1024,
            'tmp_name' => __FILE__,
            'error' => 0
        ]));
    }
}
