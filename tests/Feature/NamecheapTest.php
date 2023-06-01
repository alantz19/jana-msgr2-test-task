<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NamecheapService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Nonstandard\Uuid;
use Tests\TestCase;

class NamecheapTest extends TestCase
{
    public function test_get_tld_list_errors()
    {
        $user = User::factory()
            ->withPersonalTeam()
            ->withNamecheap()
            ->create();

        $this->actingAs($user);

        $namecheap = new NamecheapService($user);

        Http::fake([
            'https://api.sandbox.namecheap.com/*' => Http::response(file_get_contents(__DIR__ .
                '/data/namecheap/namecheap.domains.getTldList.errors.xml')),
        ]);

        $res = $namecheap->getTldList();

        $this->assertNotEmpty($res['errors']);
        $this->assertEmpty($res['tlds']);
    }

    public function test_get_tld_list()
    {
        $user = User::factory()
            ->withPersonalTeam()
            ->withNamecheap()
            ->create();

        $this->actingAs($user);

        $namecheap = new NamecheapService($user);

        Http::fake([
            'https://api.sandbox.namecheap.com/*' => Http::response(file_get_contents(__DIR__ .
                '/data/namecheap/namecheap.domains.getTldList.xml')),
        ]);

        $res = $namecheap->getTldList();

        $this->assertEmpty($res['errors']);
        $this->assertNotEmpty($res['tlds']);
    }

    public function test_create_domain_errors()
    {
        $user = User::factory()
            ->withPersonalTeam()
            ->withNamecheap()
            ->create();

        Http::fake([
            'https://api.sandbox.namecheap.com/*' => Http::response(file_get_contents(__DIR__ .
                '/data/namecheap/namecheap.domains.create.errors.xml')),
        ]);

        $namecheap = new NamecheapService($user);

        $res = $namecheap->createDomain('smsedge.com');

        $this->assertNotEmpty($res['errors']);
        $this->assertEmpty($res['result']);
    }

    public function test_create_domain()
    {
        $user = User::factory()
            ->withPersonalTeam()
            ->withNamecheap()
            ->create();

        Http::fake([
            'https://api.sandbox.namecheap.com/*' => Http::response(file_get_contents(__DIR__ .
                '/data/namecheap/namecheap.domains.create.xml')),
        ]);

        $namecheap = new NamecheapService($user);

        $res = $namecheap->createDomain('smsedge.com');

        $this->assertEmpty($res['errors']);
        $this->assertNotEmpty($res['result']);
    }
}
