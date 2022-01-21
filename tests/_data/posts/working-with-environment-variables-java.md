---
publish_date: 11.10.2021
slug: environment-variables-java
title: Working with Environment Variables in Java
synopsis: In this tutorial, learn how environment variables can be set and retrieved in Java applications.
image: environment-variables-java.png
categories:
- Java
tags:
- Environment variables
- Configuration
---
Environment variables are a great way to configure Java applications without having to explicitly store settings in code, such as for database and caching servers, or for third-party APIs.

Keeping such settings outside of the code has several distinct advantages:

- Avoids the need to update and recompile code when settings change
- Helps prevent exposing sensitive credentials, such as usernames and passwords, and deployment tokens
- You can deploy the same code in multiple environments

In this short article, I'm going to show you some of the ways of working with environment variables in Java.

## How to access environment variables in Java

One of the most common ways is to use [System.getenv()](https://docs.oracle.com/en/java/javase/17/docs/api/java.base/java/lang/System.html#getenv(java.lang.String)), which accepts an optional String argument.
Based on whether a `String` argument is passed, a different value is returned from the method. Specifically:

If a `String` is passed and it matches a key in the internal environment `Map`, then its value is returned.
If a matching key is not found, `null` is returned.
If a `String` argument is not passed, a read-only `java.util.Map` containing all environment variables and their values is returned.
The `Map`'s keys are the environment variable names and its values are the values.

> Keep in mind that different platforms operate in different ways, e.g., on UNIX, Linux, and macOS, environment variables are case-sensitive, whereas on Microsoft Windows they are not.

Below, you can see an example of how to use the method to retrieve the Linux `SHELL` environment variable (which contains the user's shell).

```java
package com.settermjd.twilio.envvars;

public class Main {
    public static void main(String[] args) {
        System.out.println(
            String.format("The current shell is: %s.", System.getenv("SHELL"))
        );
    }
}
```
