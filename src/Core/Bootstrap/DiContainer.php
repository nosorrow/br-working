<?php

/*
 * dependency injection container =>
 *
$dic = new Dic();

    $connection = new Database\Connection('lesson', 'root', 'pass');
    $dic->setInstance($connection);

    $dic->set('Database\Connection', function () {
        return new Database\Connection('Personal', 'root', 'pass');
    });

    $dic->setFactories('Model', function () use ($dic) {
        return new Model($dic->get('Database\Connection'));
    } );

    $dic->setFactories('Bar', function () use ($dic) {
        return new Bar( $dic->get('Foo'));
        });

     var_dump( $dic->get('Bar') );
 *
 */
namespace Core\Bootstrap;


class DiContainer
{

    /**
     * @var array
     */
    public $class = [];
    /**
     * @var array
     */
    public $registry = [];
    /**
     * Singleton
     * @var array
     */
    public $instances = [];
    /**
     * @var array
     */
    public $factories = [];

    /**
     * Set a Singleton instance
     * @param $key
     * @param callable $resolver
     */
    public function set($key, Callable $resolver)
    {
        $this->registry[$key] = $resolver;

    }

    /**
     * @param $key
     * @param callable $resolver
     */
    public function setFactories($key, Callable $resolver)
    {
        $this->factories[$key] = $resolver;
    }

    /**
     *
     * @param $instance
     */
    public function setInstance($instance)
    {

        $reflection = new \ReflectionClass( $instance);

        $this->instances[$reflection->getName()] = $instance;

    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key)
    {
        if (isset($this->factories[$key])) {

            return $this->factories[$key]();
        }

        // Get Singleton
        if (!isset($this->instances[$key])) {

            if (isset($this->registry[$key])) {

                $this->instances[$key] = $this->registry[$key]();

            } else {

                try{

                    $reflected_class = new \ReflectionClass($key);

                    if ($reflected_class->isInstantiable()) {

                        $constructor = $reflected_class->getConstructor();

                        if ($constructor) {

                            $parameters = $constructor->getParameters();

                            $constructor_parameters = [];

                            foreach ($parameters as $parameter) {

                                if ($parameter->getClass()) {

                                    $constructor_parameters[] = $this->get($parameter->getClass()->getName());

                                } else {

                                    $constructor_parameters[] = $parameter->getDefaultValue();
                                }
                            }
                            $this->instances[$key] = $reflected_class->newInstanceArgs($constructor_parameters);

                        } else {

                            $this->instances[$key] = $reflected_class->newInstance();

                        }

                    } else {

                        try {

                            $this->instances[$key] = $key::getInstance();

                        } catch (\Exception $e) {

                            echo 'Възникана грешка : ' . $e->getMessage() . '|' . $e->getTraceAsString();
                        }
                    }

                } catch (\Exception $e){

                    echo 'Възникана грешка в DiContainer : ' .$e->getLine() .' | ' .$e->getMessage().
                        ' | '. $e->getTraceAsString();

                }
            }
        }

        return $this->instances[$key];
    }

}