<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class OptionMatchingViewer extends Component
{
    public $options;
    public $leftOptions;
    public $rightOptions;
    public $pairs = [];

    /**
     * Create a new component instance.
     */
    public function __construct($options)
    {
        $this->options = $options;

        // Split options into left and right
        $this->leftOptions = $options->filter(fn($o) => Str::startsWith($o->option_key, 'L'));
        $this->rightOptions = $options->filter(fn($o) => Str::startsWith($o->option_key, 'R'));

        // Build pairs mapping (Left ID -> Right ID)
        $rightMap = $this->rightOptions->pluck('id', 'option_key');

        foreach ($this->leftOptions as $left) {
            $targetKey = $left->getMetadata('match_with');
            if (isset($rightMap[$targetKey])) {
                $this->pairs[] = [
                    'left' => $left->id,
                    'right' => $rightMap[$targetKey]
                ];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.option-matching-viewer');
    }
}
