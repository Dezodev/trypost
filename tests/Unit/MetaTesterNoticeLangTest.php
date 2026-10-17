<?php

use Illuminate\Support\Facades\Lang;

it('defines the Meta tester notice in every supported locale file', function (string $locale) {
    expect(Lang::has('notices.meta_tester.title', $locale, false))->toBeTrue()
        ->and(Lang::has('notices.meta_tester.description', $locale, false))->toBeTrue();
})->with(['en', 'fr']);
