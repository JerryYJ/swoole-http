<?php


namespace Core;


use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;

class BeanFactory
{
    private static  $env = []; // 配置文件
    private static $container; // ioc容器
    private static $handlers=[];

    public static function init()
    {
        // 加载配置文件
        self::$env = parse_ini_file(ROOT_PATH."/env");
        $handlers = glob(ROOT_PATH . "/core/annotationHandlers/*.php");

        foreach ($handlers as $handler) {
            self::$handlers = array_merge(self::$handlers, require_once($handler));
        }
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        self::$container = $builder->build();

        // 设置注解加载
        $loader = require(ROOT_PATH . "/vendor/autoload.php");
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);
//        $scan_dir = self::getEnv('scan_dir', ROOT_PATH . '/app');
//        $scan_root_namespace = self::getEnv('scan_root_namespace', "App\\");
        $scans = [
            ROOT_PATH . '/core/init' => "Core\\init",
            self::getEnv('scan_dir', ROOT_PATH . '/app')=>self::getEnv('scan_root_namespace', "App\\")
        ];
        foreach ($scans as $key => $value) {
            self::ScanBeans($key, $value);
        }
    }

    private static function getEnv(string $key, $default='')
    {
        if (isset(self::$env[$key])) {
            return self::$env[$key];
        }
        return $default;
    }

    public static function getBean($name)
    {
        return self::$container->get($name);
    }

    private static function ScanBeans($scan_dir, $scan_root_namespace)
    {
//        $scan_dir = self::getEnv('scan_dir', ROOT_PATH . '/app');
//        $scan_root_namespace = self::getEnv('scan_root_namespace', "App\\");
        $files = glob($scan_dir . "/*.php");
        foreach ($files as $file) {
            require_once $file;
        }

//        AnnotationRegistry::registerAutoloadNamespace("Core\\annotations");
        $reader = new AnnotationReader();
        foreach (get_declared_classes() as $class) {
            if (strstr($class, $scan_root_namespace)) {
                $ref_class = new ReflectionClass($class);
                $class_annos = $reader->getClassAnnotations($ref_class); // 获取类的注解

                ///// 处理注解类
                foreach ($class_annos as $class_anno) {
                    $handler = self::$handlers[get_class($class_anno)];
                    $instance = self::$container->get($ref_class->getName());
                    //// property annotation
                    self::handlerPropAnno($instance, $ref_class, $reader);
                    //// method annotation
                    self::handlerMethodAnno($instance, $ref_class, $reader);
                    $handler($instance, self::$container, $class_anno);
                }
            }
        }
    }

    /**
     * process property annotation
     * @param $instance
     * @param ReflectionClass $reflection_class
     * @param AnnotationReader $reader
     */
    private static function handlerPropAnno(&$instance, ReflectionClass $reflection_class, AnnotationReader $reader)
    {
        $props = $reflection_class->getProperties();
        foreach ($props as $prop) {
            $prop_annos = $reader->getPropertyAnnotations($prop);
            foreach ($prop_annos as $prop_anno) {
                $handler = self::$handlers[get_class($prop_anno)];
                $handler($prop, $instance, $prop_anno);
            }
        }
    }

    /**
     * process methods annotation
     * @param $instance
     * @param ReflectionClass $reflection_class
     * @param AnnotationReader $reader
     */
    private static function handlerMethodAnno(&$instance, ReflectionClass $reflection_class, AnnotationReader $reader)
    {
        $methods = $reflection_class->getMethods();
        foreach ($methods as $method) {
            $prop_annos = $reader->getMethodAnnotations($method);
            foreach ($prop_annos as $prop_anno) {
                $handler = self::$handlers[get_class($prop_anno)];
                $handler($method, $instance, $prop_anno);
            }
        }
    }
}