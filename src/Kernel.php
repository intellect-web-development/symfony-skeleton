<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

// todo: реструктуризовать в Common-style
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
