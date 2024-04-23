<?php

test('example', function () {
    $this->get('/')->assertUnauthorized();
});
