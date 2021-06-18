# orc
[![StyleCI](https://github.styleci.io/repos/206144416/shield?branch=master)](https://github.styleci.io/repos/206144416?branch=master)
[![Docker Image CI](https://github.com/ConductionNL/orderregistratiecomponent/workflows/Docker%20Image%20CI/badge.svg?branch=master)](https://github.com/ConductionNL/orderregistratiecomponent/actions?query=workflow%3A"Docker+Image+CI")
[![Artifacthub](https://img.shields.io/endpoint?url=https://artifacthub.io/badge/repository/orderregistratiecomponent)](https://artifacthub.io/packages/helm/orderregistratiecomponent/orderregistratiecomponent)
[![BCH compliance](https://bettercodehub.com/edge/badge/ConductionNL/orderregistratiecomponent?branch=master)](https://bettercodehub.com/)
[![Componenten Catalogus](https://img.shields.io/badge/vng--componentencatalogus-posted-green)](https://componentencatalogus.commonground.nl/componenten/11?)
[![Status badge](https://shields.api-test.nl/endpoint.svg?style=for-the-badge&url=https%3A//api-test.nl/api/v1/provider-latest-badge/d046e630-1a89-49fd-9cd6-555eccd7b776/)](https://api-test.nl/server/4/0b007860-ddf1-4dc8-905f-8fe8464fb6df/d046e630-1a89-49fd-9cd6-555eccd7b776/latest/)

Description
----
Het Order Registratie Component verzorgt de afhandeling van bestellingen met uitzondering van facturatie en betalingen. Het biedt in samenwerking met het PDC een multi-tenant en omnichannel oplossing voor het verkopen van producten en diensten. Hierbij worden offers (te kopen resources vanuit het PC) toegevoegd aan een order, waarbij het Order Registratie Component de geldigheid van de aanbieding controleert en een eventuele prijs en betaling berekeningen uitvoert.

Omdat orders ook de status “basket” kunnen hebben, neemt het component hierbij essentiële validatie van de frontend over, waardoor ontwikkelaars worden ontlast en organisaties verzekerd worden van een propere berekening.

In tandem met het VRC voorziet het order component ook in de directe afhandeling van een order. Voor een simpele bestelling kan er hierbij worden gedacht aan een orderpick proces, maar voor complexe bestellingen kunnen ook zaken of processen worden opgestart.

Additional Information
----

- [Contributing](CONTRIBUTING.md)
- [ChangeLogs](CHANGELOG.md)
- [RoadMap](ROADMAP.md)
- [Security](SECURITY.md)
- [Licence](LICENSE.md)


Installation
----
We differentiate between two way's of installing this component, a local installation as part of the provided developers toolkit or an [helm](https://helm.sh/) installation on an development or production environment.

#### Local installation
First make sure you have [docker desktop](https://www.docker.com/products/docker-desktop) running on your computer. Then clone the repository to a directory on your local machine through a [git command](https://github.com/git-guides/git-clone) or [git kraken](https://www.gitkraken.com) (ui for git). If successful you can now navigate to the directory of your cloned repository in a command prompt and execute docker-compose up.
```CLI
$ docker-compose up
```
This will build the docker image and run the used containers and when seeing the log from the php container: "NOTICE: ready to handle connections", u are ready to view the documentation at localhost on your preferred browser.

#### Instalation on Kubernetes or Haven
As a haven compliant commonground component this component is installable on kubernetes trough helm. The helm files can be found in the api/helm folder. For installing this component trough helm simply open your (still) favorite command line interface and run
```CLI
$ helm install [name] ./api/helm --kubeconfig kubeconfig.yaml --namespace [name] --set settings.env=prod,settings.debug=0,settings.cache=1
```
For an in depth installation guide you can refer to the [installation guide](/api/helm) contained with the helm files, it also contains a short tutorial on getting your cluster ready to expose your installation to the world

Standards
----

This component adheres to international, national and local standards (in that order), notable standards are:

- Any applicable [W3C](https://www.w3.org) standard, including but not limited to [rest](https://www.w3.org/2001/sw/wiki/REST), [JSON-LD](https://www.w3.org/TR/json-ld11/) and [WEBSUB](https://www.w3.org/TR/websub/)
- Any applicable [schema](https://schema.org/) standard
- [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md)
- [GAIA-X](https://www.data-infrastructure.eu/GAIAX/Navigation/EN/Home/home.html)
- [Publiccode](https://docs.italia.it/italia/developers-italia/publiccodeyml-en/en/master/index.html), see the [publiccode](api/public/schema/publiccode.yaml) for further information
- [Forum Stanaardisatie](https://www.forumstandaardisatie.nl/open-standaarden)
- [NL API Strategie](https://docs.geostandaarden.nl/api/API-Strategie/)
- [Common Ground Realisatieprincipes](https://componentencatalogus.commonground.nl/20190130_-_Common_Ground_-_Realisatieprincipes.pdf)
- [Haven](https://haven.commonground.nl/docs/de-standaard)
- [NLX](https://docs.nlx.io/understanding-the-basics/introduction)
- [Standard for Public Code](https://standard.publiccode.net/), see the [compliancy scan](publiccode.md) for further information.

This component is based on the following [schema.org](https://schema.org) sources:
- [Address](https://schema.org/PostalAddress)
- [Person](https://schema.org/Person)

Developers toolkit and technical information
----
You can find the data model, OAS documentation and other helpfull developers material like a  postman collection under api/public/schema, development support is provided trough the [samenorganiseren slack channel](https://join.slack.com/t/samenorganiseren/shared_invite/zt-dex1d7sk-wy11sKYWCF0qQYjJHSMW5Q).

Couple of quick tips when you start developing
- If you not yet have setup the component locally read the Tutorial for setting up your local environment.
- You can find the other components on [Github](https://github.com/ConductionNL).
- Take a look at the [commonground componenten catalogus](https://componentencatalogus.commonground.nl/componenten?) to prevent development collitions.
- Use [Commongroun.conduction.nl](https://commonground.conduction.nl/) for easy deployment of test environments to deploy your development to.
- For information on how to work with the component you can refer to the tutorial [here](TUTORIAL.md).


Contributing
----
First of al please read the [Contributing](CONTRIBUTING.md) guideline's ;)

But most imporantly, welcome! We strife to keep an active community at [commonground.nl](https://commonground.nl/), please drop by and tell is what you are thinking about so that we can help you along.


Credits
----
Information about the authors of this component can be found [here](AUTHORS.md)

Copyright © [Utrecht](https://www.utrecht.nl/) 2019
