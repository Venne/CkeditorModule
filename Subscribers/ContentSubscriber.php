<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\CkeditorModule\Subscribers;

use Nette\DI\Container;
use Doctrine\Common\EventSubscriber;
use App\CoreModule\Events\ContentHelperArgs;
use App\CoreModule\Events\ContentHelperEvents;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ContentSubscriber implements EventSubscriber
{

	/** @var Container */
	protected $container;
	
	/** @var string */
	protected $basePath;
	
	/** @var ITemplateConfigurator */
	protected $templateConfigurator;

	/** @var \Nette\Application\Application */
	protected $application;

	/** @var array */
	protected $patternsSave = array();
	
	/** @var array */
	protected $patternsLoad = array();



	/**
	 * @param \Nette\Application\Application $application
	 */
	public function __construct(Container $container, \Nette\Application\Application $application, \Venne\Templating\ITemplateConfigurator $templateConfigurator)
	{
		$this->container = $container;
		$this->application = $application;
		$this->templateConfigurator = $templateConfigurator;
		
		
		$baseUrl = rtrim($this->container->httpRequest->getUrl()->getBaseUrl(), '/');
		$this->basePath = preg_replace('#https?://[^/]+#A', '', $baseUrl);
		
		$this->patternsLoad = array(
			'/src="{\$basePath}\//' => 'src="'.$this->basePath.'/',
			'/href="{\$basePath}\//' => 'href="'.$this->basePath.'/',
			'/{=\'([^\']*)\'[ ]*?[|][ ]*?resize:[^\}]*}/i' => '${1}',
		);
		
		$this->patternsSave = array(
			'/src="'.str_replace("/", "\/", $this->basePath).'\//' => 'src="{$basePath}/',
			'/href="'.str_replace("/", "\/", $this->basePath).'\//' => 'href="{$basePath}/',
			'/(?:src="\{\$basePath\}\/([^"]*)"[ ]*)style="([ ]*(?:width:[ ]*(\d+)px;[ ]*)*(?:height:[ ]*(\d+)px;[ ]*)*(?:width:[ ]*(\d+)px;[ ]*)*)"/i' => 'src="{$basePath}/{=\'${1}\'|resize:${3}${5},${4}}" style="${2}" ',
		);
	}



	/**
	 * Array of events.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(
			ContentHelperEvents::onContentRender,
			ContentHelperEvents::onContentLoad,
			ContentHelperEvents::onContentSave,
		);
	}



	/**
	 * onContentRender event.
	 *
	 * @param ContentHelperArg $args
	 */
	public function onContentRender(ContentHelperArgs $args)
	{
		$text = $args->getText();

		$template = $this->application->getPresenter()->createTemplate("\Nette\Templating\Template");
		$template->setSource($text);
		$args->setText($template->__toString());
	}



	/**
	 * onContentLoad event.
	 *
	 * @param ContentHelperArg $args
	 */
	public function onContentSave(ContentHelperArgs $args)
	{
		//echo (htmlentities($args->getText())) . "<br />";
		$args->setText(
			preg_replace(array_keys($this->patternsSave), array_merge($this->patternsSave), $args->getText())
		);
		//echo (htmlentities($args->getText()));
		//die();
	}



	/**
	 * onContentSave event.
	 *
	 * @param ContentHelperArg $args
	 */
	public function onContentLoad(ContentHelperArgs $args)
	{
		$args->setText(
				preg_replace(array_keys($this->patternsLoad), array_merge($this->patternsLoad), $args->getText())
		);
	}

}
