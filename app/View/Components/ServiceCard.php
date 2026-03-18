<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $price = '',
        public string $duration = '',
        public ?string $icon = null,
        public ?string $image = null,
        public bool $showBookButton = true,
        public int $serviceId = 0
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.service-card');
    }
}
