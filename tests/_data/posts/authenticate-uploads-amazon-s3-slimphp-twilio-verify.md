---
publish_date: 17.01.2022
slug: authenticate-uploads-amazon-s3-slimphp
synopsis: Learn how to use Twilio's Verify API to validate uploads to an Amazon S3 bucket in a Slim framework application, which uses the Twig templating engine.

title: Authenticate Uploads to Amazon S3 in SlimPHP Using Twilio Verify
image: authenticate-uploads-amazon-s3-slimphp.png
categories:
- PHP
tags:
- Cloud
- Security
- Slim Framework
- Twig
- Twilio Verify
---
It's hard to get away from cloud storage in modern web application development; and with good reason!

Infrastructure as a Service (IaaS) providers such as _Amazon Web Services_, _Microsoft Azure_, _Google Cloud Platform_, and _Alibaba Cloud_ make storing data anywhere in the world almost trivial, regardless of programming language, operating system, and budget.

Whether you're storing instrumentation data from a Raspberry Pi-based weather station, or medical records for a nation-state, cloud providers are up to the task. However, they don't do everything for you. They won’t handle tasks such as only letting valid users store legitimate files.

So in this tutorial, you're going to learn how to use [Twilio's Verify API](https://www.twilio.com/docs/verify/api) to validate uploads to an Amazon S3 bucket in a [Slim framework](https://www.slimframework.com/) application which uses [the Twig templating engine](https://twig.symfony.com/) for the view templates.
