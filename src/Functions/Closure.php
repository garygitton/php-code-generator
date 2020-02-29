<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Traits\FunctionTrait;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use Murtukov\PHPCodeGenerator\Traits\ScopedContentTrait;

class Closure implements GeneratorInterface
{
    use FunctionTrait;
    use ScopedContentTrait;
    use IndentableTrait;

    private array   $uses = []; // variables of parent scope

    public static function create()
    {
        return new self();
    }

    public function generate(): string
    {
        $code = <<<CODE
        function ({$this->generateArgs()}){$this->buildUses()}{$this->buildReturnType()} {
        {$this->generateContent()}
        }
        CODE;
        return $code;
    }

    private function buildUses(): string
    {
        if (count($this->uses) > 0) {
            $last = array_key_last($this->uses);

            $code = '';
            foreach ($this->uses as $key => $var) {
                $code .= "$$var";

                if ($key !== $last) {
                    $code .= ', ';
                }
            }

            return " use ($code)";
        }

        return '';
    }

    private function buildReturnType()
    {
        return $this->returnType ? ": $this->returnType" : '';
    }

    public function __toString(): string
    {
        return $this->generate();
    }
}