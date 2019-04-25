<?php
namespace BEAR\ApiDoc;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function implode;
use function is_array;
use function json_encode;
use function sprintf;

final class ConstrainExtention extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('constrain', [$this, 'constrain'])
        ];
    }

    public function constrain(array $prop) : string
    {
        $consrains = [];
        foreach ($prop as $key => $item) {
            if ($key[0] === '$' || $key === 'type') {
                continue;
            }
            $consrainVal = is_array($item) ? json_encode($item) : (string) $item;
            $consrains[] = sprintf('%s:%s', $key, $consrainVal);
        }

        return implode(', ', $consrains);
    }
}
