<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The Kernel class represents the central part of the application.
 *
 * This Kernel class is an extension of Symfony's BaseKernel class. It uses the
 * MicroKernelTrait,
 * a Symfony utility that brings a slim and small shape Kernel with an efficient
 * method configuration system.
 *
 * MicroKernelTrait allows for defining your entire Symfony application in one
 * single PHP file.
 *
 * BaseKernel class provides a structure for building applications as it
 * includes methods for registering
 * and booting bundles, handling requests and allowing bundles to override parts
 * of the application.
 *
 * Kernel class is usually the place where you configure the application's
 * services and routes.
 *
 * The use of MicroKernelTrait indicates that this Kernel is going to be a micro
 * kernel. A micro kernel
 * is a minimalistic kernel where most functionalities are implemented as
 * services rather than built into
 * the kernel itself.
 *
 * This class is part of the Blog application and is subject to the MIT license
 * that is bundled with this
 * source code in the file LICENSE. It was developed by Antoine Greuzard.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
