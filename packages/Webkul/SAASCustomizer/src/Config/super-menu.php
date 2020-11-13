<?php

return [
    [
        'key' => 'tenants',
        'name' => 'saas::app.super-user.layouts.left-menu.tenants',
        'route' => 'super.tenants.index',
        'sort' => 3,
        'icon-class' => 'company-icon',
    ], [
        'key' => 'tenants.companies',
        'name' => 'saas::app.super-user.layouts.left-menu.tenants',
        'route' => 'super.tenants.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'tenants.customers',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-customers',
        'route' => 'super.tenants.customers.index',
        'sort' => 2,
        'icon-class' => '',
    ], [
        'key' => 'tenants.products',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-products',
        'route' => 'super.tenants.products.index',
        'sort' => 3,
        'icon-class' => '',
    ], [
        'key' => 'tenants.orders',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-orders',
        'route' => 'super.tenants.orders.index',
        'sort' => 3,
        'icon-class' => '',
    ], [
        'key' => 'settings',
        'name' => 'saas::app.super-user.layouts.left-menu.settings',
        'route' => 'super.agents.index',
        'sort' => 4,
        'icon-class' => 'settings-icon'
    ], [
        'key' => 'settings.agents',
        'name' => 'saas::app.super-user.layouts.left-menu.agents',
        'route' => 'super.agents.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'settings.locales',
        'name' => 'saas::app.super-user.layouts.left-menu.locales',
        'route' => 'super.locales.index',
        'sort' => 2,
        'icon-class' => ''
    ], [
        'key' => 'settings.currencies',
        'name' => 'saas::app.super-user.layouts.left-menu.currencies',
        'route' => 'super.currencies.index',
        'sort' => 3,
        'icon-class' => ''
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'saas::app.super-user.layouts.left-menu.exchange-rates',
        'route' => 'super.exchange_rates.index',
        'sort' => 4,
        'icon-class' => ''
    ], [
        'key' => 'settings.channels',
        'name' => 'saas::app.super-user.layouts.left-menu.channels',
        'route' => 'super.channels.index',
        'sort' => 5,
        'icon-class' => ''
    ], [
        'key' => 'configuration',
        'name' => 'saas::app.super-user.layouts.left-menu.configurations',
        'route' => 'super.configuration.index',
        'sort' => 5,
        'icon-class' => 'configuration-icon'
    ], [
        'key' => 'configuration.general',
        'name' => 'saas::app.super-user.layouts.left-menu.general',
        'route' => 'super.configuration.index',
        'sort' => 1,
        'icon-class' => ''
    ]
];