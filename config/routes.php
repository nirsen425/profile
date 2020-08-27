<?php

return [
    'profiles/create' => 'profile/create',
    'profiles/store' => 'profile/store',
    'profiles/edit/(\d+)' => 'profile/edit/$1',
    'profiles/update/(\d+)' => 'profile/update/$1',
    'profiles/show/(\d+)' => 'profile/show/$1',
    'profiles/delete/(\d+)' => 'profile/delete/$1',
    'profiles' => 'profile/index',
];