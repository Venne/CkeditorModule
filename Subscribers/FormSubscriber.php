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

use Doctrine\Common\EventSubscriber;
use Venne\Forms\Events\Events;
use Venne\Forms\Events\EventArgs;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormSubscriber implements EventSubscriber {


	/** @var \Venne\Assets\AssetManager */
	protected $assetManager;



	/**
	 * Constructor.
	 *
	 * @param \Venne\Assets\AssetManager $assetManager
	 */
	public function __construct(\Venne\Assets\AssetManager $assetManager)
	{
		$this->assetManager = $assetManager;
	}



	/**
	 * Array of events.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(Events::onBeforeRender);
	}



	/**
	 * onBeforeRender event.
	 *
	 * @param EventArgs $args
	 */
	public function onBeforeRender(EventArgs $args)
	{
		$form = $args->getForm();

		foreach ($form->getComponents() as $component) {
			if ($component instanceof \Nette\Forms\Controls\TextArea && isset($component->getControlPrototype()->data["venne-form-editor"])) {
				$this->assetManager->addJavascript("@CkeditorModule/ckeditor/ckeditor.js");
				$this->assetManager->addJavascript("@CkeditorModule/ckeditor/adapters/jquery.js");
				$this->assetManager->addJavascript("@CkeditorModule/adapter.js");
			}
		}
	}

}
