<?php

use App\Traits\GeneratesPdfTrait;

it('formats paragraph notes with valid line break tags', function () {
    $formatter = new class
    {
        use GeneratesPdfTrait;

        public function format(string $content): string
        {
            return $this->getFormattedString($content);
        }

        public function getExtraFields(): array
        {
            return [];
        }

        public function getFieldsArray(): array
        {
            return [];
        }
    };

    expect($formatter->format('<p>First line</p><p>Second line</p>'))
        ->toContain('First line')
        ->toContain('Second line')
        ->toContain('<br>')
        ->not->toContain('</br>');
});
