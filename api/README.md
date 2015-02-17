# PHP API

*This API is controlled by Slim PHP as it is a micro-framework that is amazing for jobs like this.*

I have included a couple of packages into this project to ease and speed up development, these are :

* Twig templating engine, to help render the 404 and admin pages.
* Lusitanian's OAuth package to connect all the OAuth API's that we may require.

**The whole of the `src/` directory is kept to one side for all custom code, for example Classes and Views.**

The **namespace** for this application is going to be `namespace OpenDev;`

THe URI structure is going to follow a simple but useful pattern :

* /api/v1/posts/
* /api/v1/posts/{:id}
* /api/v1/categories/
* /api/v1/categories/{:id}
* /api/v1/authors/
* /api/v1/authors/{:id}
