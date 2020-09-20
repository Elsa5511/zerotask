<?php

namespace Application\View;

use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use BjyAuthorize\Guard\Route;
use BjyAuthorize\Guard\Controller;

/**
 * Dispatch error handler, catches exceptions related with authorization and
 * redirects the user agent to a configured location
 */
class MixedStrategy implements ListenerAggregateInterface
{

    /**
     * @var string route to be used to handle redirects
     */
    protected $loginRedirectRoute = 'zfcuser/login';

    /**
     * @var string URI to be used to handle redirects
     */
    protected $redirectUri;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var string
     */
    protected $template;

    /**
     * @param string $template name of the template to use on unauthorized requests
     */
    public function __construct($config)
    {
        $this->auth = $config['auth'];
        $this->template = (string) $config['template'];
        $this->afterLoginRedirectRoute = $config['after_login_redirect_route'];
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), -5000);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = (string) $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $redirectRoute
     */
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = (string) $redirectRoute;
    }

    /**
     * @param string|null $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = (string) $redirectUri;
    }

    /**
     * Handles redirects in case of dispatch errors caused by unauthorized access
     *
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function onDispatchError(MvcEvent $event)
    {
        $result = $event->getResult();
        $routeMatch = $event->getRouteMatch();
        $response = $event->getResponse();
        $router = $event->getRouter();
        $error = $event->getError();

        if ($this->auth->hasIdentity()) {

            if ($routeMatch->getMatchedRouteName() === $this->loginRedirectRoute) {
                $url = $router->assemble(array(), array('name' => $this->afterLoginRedirectRoute));

                $response = $response ? : new Response();

                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);

                $event->setResponse($response);
            } else {
                // Do nothing if the result is a response object

                /*if ($result instanceof Response || ($response && !$response instanceof HttpResponse)) {
                    return;
                }*/

                // Common view variables
                $viewVariables = array(
                    'error' => $event->getParam('error'),
                    'identity' => $event->getParam('identity'),
                );

                switch ($event->getError()) {
                    case Controller::ERROR:
                        $viewVariables['controller'] = $event->getParam('controller');
                        $viewVariables['action'] = $event->getParam('action');
                        break;
                    case Route::ERROR:
                        $viewVariables['route'] = $event->getParam('route');
                        break;
                    case Application::ERROR_EXCEPTION:
                        if (!($event->getParam('exception') instanceof UnAuthorizedException)) {
                            return;
                        }

                        $viewVariables['reason'] = $event->getParam('exception')->getMessage();
                        $viewVariables['error'] = 'error-unauthorized';
                        break;
                    default:
                        /*
                         * do nothing if there is no error in the event or the error
                         * does not match one of our predefined errors (we don't want
                         * our 403 template to handle other types of errors)
                         */

                        return;
                }

                $model = new ViewModel($viewVariables);
                $response = $response ? : new HttpResponse();

                $model->setTemplate($this->getTemplate());
                $event->getViewModel()->addChild($model);
                $response->setStatusCode(403);
                $event->setResponse($response);
            }
        } else {
            $redirectUri = (null === $this->redirectUri ? $_SERVER['REQUEST_URI'] : $this->redirectUri);

            if ($result instanceof Response || !$routeMatch || ($response && !$response instanceof Response) || !(
                    Route::ERROR === $error || Controller::ERROR === $error || (
                    Application::ERROR_EXCEPTION === $error && ($event->getParam('exception') instanceof UnAuthorizedException)
                    )
                    )
            ) {
                return;
            }

            $url = $router->assemble(array(), array('name' => $this->loginRedirectRoute));

            $url .= '?redirect=' . $redirectUri;

            $response = $response ? : new Response();

            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            $event->setResponse($response);
        }
    }

}

