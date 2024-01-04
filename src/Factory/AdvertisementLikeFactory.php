<?php

namespace App\Factory;

use App\Entity\AdvertisementLike;
use App\Repository\AdvertisementLikeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<AdvertisementLike>
 *
 * @method        AdvertisementLike|Proxy                     create(array|callable $attributes = [])
 * @method static AdvertisementLike|Proxy                     createOne(array $attributes = [])
 * @method static AdvertisementLike|Proxy                     find(object|array|mixed $criteria)
 * @method static AdvertisementLike|Proxy                     findOrCreate(array $attributes)
 * @method static AdvertisementLike|Proxy                     first(string $sortedField = 'id')
 * @method static AdvertisementLike|Proxy                     last(string $sortedField = 'id')
 * @method static AdvertisementLike|Proxy                     random(array $attributes = [])
 * @method static AdvertisementLike|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AdvertisementLikeRepository|RepositoryProxy repository()
 * @method static AdvertisementLike[]|Proxy[]                 all()
 * @method static AdvertisementLike[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static AdvertisementLike[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static AdvertisementLike[]|Proxy[]                 findBy(array $attributes)
 * @method static AdvertisementLike[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static AdvertisementLike[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AdvertisementLikeFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(AdvertisementLike $advertisementLike): void {})
        ;
    }

    protected static function getClass(): string
    {
        return AdvertisementLike::class;
    }
}
