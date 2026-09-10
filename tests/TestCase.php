<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Garante que os planos (free/pro/premium) existam nos testes que usam
     * RefreshDatabase, já que SubscriptionService depende deles.
     */
    protected $seed = true;
}
