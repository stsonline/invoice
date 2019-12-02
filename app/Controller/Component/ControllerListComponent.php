<?php

class ControllerListComponent extends Component {

	public function getList(Array $controllersToExclude = array('PagesController')) {
		$controllersToExclude[] = 'AppController';
		$controllerClasses = array_filter(App::objects('Controller'), function ($controller) use ($controllersToExclude) {
			return !in_array($controller, $controllersToExclude);
		});
		$result = array();

		foreach($controllerClasses as $controller) {
			$controllerName = str_replace('Controller', '', $controller);
			$result[$controller]['name'] = $controllerName;
			$result[$controller]['displayName'] = Inflector::humanize(Inflector::underscore($controllerName));
			$result[$controller]['actions'] = $this->getActions($controller);
		}

		return $result;
	}

	private function getActions($controller) {
		App::uses($controller, 'Controller');
		$methods = get_class_methods($controller);
		$methods = $this->removeParentMethods($methods);
		$methods = $this->removePrivateActions($methods);

		return $methods;
	}

	private function removeParentMethods($methods) {
		$appControllerMethods = get_class_methods('AppController');

		return @array_diff($methods, $appControllerMethods);
	}

	private function removePrivateActions($methods) {
		return @array_filter($methods, function ($method) {
			return $method{0} != '_';
		});
	}
}