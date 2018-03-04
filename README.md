# I'm Hungry - API
> Sistema de controle de pedidos para Praças de alimentação e Food Truck

[![NPM Version][npm-image]][npm-url]
[![Build Status][travis-image]][travis-url]
[![Downloads Stats][npm-downloads]][npm-url]

API que receberá todas as requisições para o sistema I'm Hungry.  
Desenvolvida em PHP usando as seguintes tecnologias: 

1. Slim-Framework v3.0
2. JWT - Firebase/php v5.0.0
3. PhpMailer v6.0
4. Verot/class.upload.php v0.33

![](header.png)

## Instalação e uso

Bibliotecas gerenciadas através do Composer.  
Terminal:

```sh
composer require slim/slim "^3.0"
composer require firebase/php-jwt
composer require phpmailer/phpmailer
```

Para Verot/class.upload.php editar o composer.json:

```sh
{
    "require": {
       "verot/class.upload.php": "dev-master"
    }
}
```

Caso não tenha o Composer instalado em seu computador:

```sh
https://getcomposer.org
```

## Histórias de Lançamento

* 1.0 - (04/03/2018)
    * Implementação: Sistema implementado na sua versão inicial.


## Dev

Dayvson – [I'm Hungry](https://www.site.com/dayvson) – dayvsondw@hotmail.com  
Hélio Junior – [I'm Hungry](https://www.site.com/helio) –  heliofsjr18@gmail.com  
Ismael Gomes – [I'm Hungry](https://www.site.com/jobs) – ismaelgomes800@gmail.com  
Rafael Eduardo – [I'm Hungry](https://www.site.com/rafael) – paracafa@gmail.com   
Rafael Freitas – [I'm Hungry](https://www.site.com/rafael) – rafael.vasconcelos@outlook.com  

Desenvolvido pela I'm Hungry. Consulte a ``LICENÇA`` para mais informações.

[https://bitbucket.org/rafavfreitas](https://bitbucket.org/rafavfreitas)

## Contribuição

1. Fork it (<https://bitbucket.org/rafavfreitas/im-hungry-api/>)
2. Crie sua feature branch (`git checkout -b feature/fooBar`)
3. Commit suas mudanças (`git commit -am 'Adicione as mudanças'`)
4. Faça o Push para a branch (`git push origin feature/branch`)
5. Crie um new Pull Request

<!-- Markdown link & img dfn's -->
[npm-image]: https://img.shields.io/npm/v/datadog-metrics.svg?style=flat-square
[npm-url]: https://npmjs.org/package/datadog-metrics
[npm-downloads]: https://img.shields.io/npm/dm/datadog-metrics.svg?style=flat-square
[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics
[wiki]: https://github.com/yourname/yourproject/wiki

