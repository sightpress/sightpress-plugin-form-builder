<?php

declare(strict_types=1);

namespace SightPress\Plugin\FormBuilder\Tests;

use SightPress\Plugin\Core\Manifest\ManifestReader;
use SightPress\Plugin\Core\Value\Locale;
use SightPress\Plugin\FormBuilder\FormBuilderPlugin;

/**
 * The plugin with the core's engine out of the picture: what the bundle and
 * the manifest promise. "Disabled" means the core never collects the plugin's
 * services, so a freshly generated plugin must have nothing that runs on its own.
 */
final class PluginBundleTest extends \PHPUnit\Framework\TestCase
{
    use SkipsInsideTemplate;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->skipWhenTemplateItself(\dirname(__DIR__).'/composer.json');
    }

    public function testBundleReadsItsManifestFromComposerJson(): void
    {
        $plugin = new FormBuilderPlugin();
        $manifest = $plugin->getManifest();

        self::assertSame($manifest->id, $plugin->getPluginId());
        self::assertSame(FormBuilderPlugin::class, $manifest->bundleClass);
        self::assertSame(\dirname(__DIR__), $plugin->getPath());
    }

    public function testManifestShipsEveryLaunchLocale(): void
    {
        $manifest = new FormBuilderPlugin()->getManifest();

        self::assertEqualsCanonicalizing(Locale::LAUNCH, $manifest->locales);
    }

    public function testEveryCapabilityIsPrefixedWithThePluginId(): void
    {
        $manifest = new FormBuilderPlugin()->getManifest();

        self::assertNotSame([], $manifest->capabilities);
        foreach ($manifest->capabilities as $capability => $roles) {
            self::assertStringStartsWith($manifest->id.'.', $capability);
            self::assertNotSame([], $roles, \sprintf('Capability "%s" needs default roles.', $capability));
        }
    }

    public function testPluginDeclaresNoOutboundHostsUntilItNeedsOne(): void
    {
        self::assertSame([], new FormBuilderPlugin()->getManifest()->outboundHosts);
    }

    public function testPluginNeedsNoOtherPluginUnlessItSaysSo(): void
    {
        $manifest = new FormBuilderPlugin()->getManifest();

        self::assertTrue(array_is_list($manifest->requires));
        foreach ($manifest->requires as $required) {
            self::assertMatchesRegularExpression('/^(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)?[a-z0-9]+(?:-[a-z0-9]+)*$/', $required);
        }
    }

    public function testManifestReaderAcceptsTheComposerFile(): void
    {
        $manifest = new ManifestReader()->readFile(\dirname(__DIR__).'/composer.json');

        self::assertSame('GPL-2.0-or-later', $manifest->license);
    }
}
