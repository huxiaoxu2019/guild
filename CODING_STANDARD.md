# Coding Standard

This project follows PHP-FIG standards, which is also followed by the zend framework. So you should refer to the [zend framework source code](https://github.com/zendframework/zendframework).

Please see at [PSR-1: Basic Coding Standard](http://www.php-fig.org/psr/psr-1/#4-class-constants-properties-and-methods) && [PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/)

## Supllement for the 7th point at PSR-2

- Declaration of global variables and global constants

EG:
```
$global_vars;

define("GLOBAL_CONTANTS", "some value");
```

- Declaration of functions

EG:
```
function function_name($arg1, $arg2, $arg3 = true)
{
    // ...
}
```

- Operators and assignment

need adding

- Inter-line alignment

need adding

- Comments and documentation blocks

    - 格式
    
    所有文档块 ("docblocks") 必须和 phpDocumentor 格式兼容，phpDocumentor 格式的描述超出了本文档的范围，关于它的详情，参考：» [http://phpdoc.org/](http://phpdoc.ofg/)。
    
    所有类文件必须在文件的顶部包含文件级 （"file-level"）的 docblock ，在每个类的顶部放置一个 "class-level" 的 docblock。下面是一些例子：

    - 文件
    
    每个包含 PHP 代码的文件必须至少在文件顶部的 docblock 包含这些 phpDocumentor 标签：
    
    ```
    /**
     * 文件的简短描述
     *
     * 文件的详细描述（如果有的话）... ...
     *
     * LICENSE: 一些 license 信息
     *
     * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
     * @license    http://framework.zend.com/license/3_0.txt   BSD License
     * @version    $Id:$
     * @link       http://framework.zend.com/package/PackageName
     * @since      File available since Release 1.5.0
    */
    ```
    
    - 类
    
    每个类必须至少包含这些 phpDocumentor 标签：
    
    ```
    /**
     * 类的简述
     *
     * 类的详细描述 （如果有的话）... ...
     *
     * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
     * @license    http://framework.zend.com/license/   BSD License
     * @version    Release: @package_version@
     * @link       http://framework.zend.com/package/PackageName
     * @since      Class available since Release 1.5.0
     * @deprecated Class deprecated in Release 2.0.0
     */
    ```
    
    - 函数
    
    每个函数，包括对象方法，必须有最少包含下列内容的文档块（docblock）：
    
        - 函数的描述
    
        - 所有参数
    
        - 所有可能的返回值
    
    因为访问级已经通过 "public"、 "private" 或 "protected" 声明， 不需要使用 "@access"。
    
    如果函数/方法抛出一个异常，使用 @throws 于所有已知的异常类：
    
    ```
    @throws exceptionclass [description]
    ```
    
- Class name prefixes and suffixes

need adding

- Best practices

need adding
