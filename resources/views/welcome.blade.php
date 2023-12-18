@extends('layouts.app')

@section('content')
    <x-moonshine::grid>
        <x-moonshine::column adaptiveColSpan="3" colSpan="3">
            <x-moonshine::card
                    url="#"
                    :overlay="true"
                    thumbnail="https://moonshine-laravel.com/images/image_1.jpg"
                    :title="fake()->sentence(3)"
                    :subtitle="date('d.m.Y')"
                    :values="['ID' => 1, 'Author' => fake()->name()]"
            >
                <x-slot:header>
                    <x-moonshine::badge color="green">new</x-moonshine::badge>
                </x-slot:header>

                {{ fake()->text() }}

                <x-slot:actions>
                    <x-moonshine::link-button href="#">Read more</x-moonshine::link-button>
                </x-slot:actions>
            </x-moonshine::card>
        </x-moonshine::column>
        <x-moonshine::column adaptiveColSpan="3" colSpan="3">
            <x-moonshine::card
                    url="#"
                    :overlay="true"
                    thumbnail="https://moonshine-laravel.com/images/image_1.jpg"
                    :title="fake()->sentence(3)"
                    :subtitle="date('d.m.Y')"
                    :values="['ID' => 1, 'Author' => fake()->name()]"
            >
                <x-slot:header>
                    <x-moonshine::badge color="green">new</x-moonshine::badge>
                </x-slot:header>

                {{ fake()->text() }}

                <x-slot:actions>
                    <x-moonshine::link-button href="#">Read more</x-moonshine::link-button>
                </x-slot:actions>
            </x-moonshine::card>
        </x-moonshine::column>
        <x-moonshine::column adaptiveColSpan="3" colSpan="3">
            <x-moonshine::card
                    url="#"
                    :overlay="true"
                    thumbnail="https://moonshine-laravel.com/images/image_1.jpg"
                    :title="fake()->sentence(3)"
                    :subtitle="date('d.m.Y')"
                    :values="['ID' => 1, 'Author' => fake()->name()]"
            >
                <x-slot:header>
                    <x-moonshine::badge color="green">new</x-moonshine::badge>
                </x-slot:header>

                {{ fake()->text() }}

                <x-slot:actions>
                    <x-moonshine::link-button href="#">Read more</x-moonshine::link-button>
                </x-slot:actions>
            </x-moonshine::card>
        </x-moonshine::column>
    </x-moonshine::grid>
@endsection