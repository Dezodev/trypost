<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('shows the terms page to guests', function () {
    $this->get(route('legal.terms'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legal/Terms')
            ->has('contactEmail'));
});

it('shows the privacy page to guests', function () {
    $this->get(route('legal.privacy'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legal/Privacy')
            ->has('contactEmail'));
});
