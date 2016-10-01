<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 01/10/16
 * Time: 11:32
 */

namespace AppBundle\units\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class AppExtensionTest
 *
 * @package AppBundle\units\DependencyInjection
 */
class AppExtensionTest extends \PHPUnit_Framework_TestCase
{
	const SERVICE_NAMES = [
		'app.service.save.booking',
		'app.service.find.booking',
		'app.service.provider.holider',
		'app.form.type.booking',
		'app.manager.booking',
		'app.validator.forbidden_dates',
		'app.form.handler.booking'
	];

	private $extension;
	private $container;

	protected function setUp()
	{
		$this->extension = new AppExtension();

		$this->container = new ContainerBuilder();
		$this->container->registerExtension($this->extension);

		$this->extension->load([], $this->container);
	}

	public function testAllRequiredServicesAreDeclared()
	{
		foreach (self::SERVICE_NAMES as $serviceName){
			$this->assertTrue($this->container->has($serviceName));
		}
	}
}