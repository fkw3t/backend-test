<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function dataProviderDocument(): array
    {
        return [
            [ [ 
                'unformatted' => '12345678901',
                'expected' => '123.456.789-01'
            ] ],
            [ [ 
                'unformatted' => '12345678901234',
                'expected' => '12.345.678/9012-34'
            ] ],
        ];
    }

    /** 
     * @dataProvider dataProviderDocument
    */
    public function test_document_should_be_formatted(array $data): void
    {
        $user = new User([
            'document_id' => $data['unformatted']
        ]);
        
        $this->assertSame($data['expected'], $user->document_id);
    }
}
