<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');