Intracto Elastic Synonym
============

This bundle integrates `intracto/elastic-synonym` into your application.

Installation
============

```console
$ composer require intracto/elastic-synonym-bundle
```

Prepare your project
========================
See `intracto/elastic-synonym` to prepare your project for this bundle.

Configuration
=============

```yaml
# packages/intracto_elastic_synonym.yaml

intracto_elastic_synonym:
  synonym_configs:

    default: # unique config identifier 
      name: Synonyms # user-friendly name
      file: '%env(string:INTRACTO_ELASTIC_SYNONYM_DIR)%synonyms.txt' # absolute path to synonym file [only configs with valid files will be accessible]
      indices: ["index"] # array of all indices that need to be refreshed on change
```

```
# .env

###> INTRACTO ELASTIC SYNONYM ###
INTRACTO_ELASTIC_SYNONYM_DIR=/vagrant/.elastic-synonym/
###> INTRACTO ELASTIC SYNONYM ###
```
Routes
======
The available actions can be added to your routing manually, or you can include and prefix the default:
```yaml
# routes/intracto_elastic_synonym.yaml

_intracto_elastic_synonym_bundle:
  resource: '@IntractoElasticSynonymBundle/Resources/config/routes.xml'
  prefix: /elastic
```

Security
========
This bundle is supposed behind authentication, you may enforce this any way you want.


Override layout
===============
The layout can be overridden by creating the file `templates/bundles/IntractoElasticSynonymBundle/base.html.twig`.
Just make sure implement `{% block intracto_elastic_synonym_content %}{% endblock %}`.
Example:
```twig
{% extends 'base.html.twig' %}
{% trans_default_domain 'IntractoElasticSynonym' %}

{% block title %}{{ 'config.index.title'|trans }}{% endblock %}

{% block body %}
    {% block intracto_elastic_synonym_content %}{% endblock %}
{% endblock %}
```
