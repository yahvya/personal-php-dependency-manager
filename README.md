# PHP Dependency Manager

> A lightweight dependency injection library for managing and resolving typed dependencies in PHP projects.

---

## 📄 Library Details

- 👤 **Author and maintainer**: Yahaya Bathily - [GitHub](https://github.com/yahvya)
- 📄 **License**: MIT
- 🧾 **License usage**: Free to use in both commercial and non-commercial projects under the MIT license.
- 🗓️ **Created at**: 15/08/2025

---

## 📚 Documentation

The library consists of three main classes:

- `Yahay\PhpDependencyInjector\DependencyManager`
- `Yahay\PhpDependencyInjector\DependencyBuilder`
- `Yahay\PhpDependencyInjector\DependencyMap`

---

### 🧩 DependencyMap

This class lets you register all your dependencies — including those that are challenging to instantiate manually.  
It provides the following methods:

- `register()` — registers a dependency that should be rebuilt on each call.
- `registerSingleton()` — registers a dependency that should be built only once and reused.

These methods take a builder closure wrapped in a `BuilderDataDto` instance. The closure can receive any class from your application (registered or not).

Example:

```
$manager->dependenciesMap->register(
    class: FakeOne::class,
    builderData: new BuilderDataDto(
        builder: fn(FakeTwo $two, AnyClassThere $c) => new FakeOne(fakeTwo: $two)
    )
);
```

---

### 🛠️ DependencyBuilder

This class is responsible for building dependencies — even those not explicitly registered. It provides two key methods:

- `build()`  
  Accepts a `BuilderDataDto`, resolves the dependencies required by the builder closure, and returns the result.  
  Since PHP built-in types (e.g., `string`, `int`) cannot be auto-instantiated, you can pass manual values via the `manualParameters` property of the DTO.

Example:

```
new BuilderDataDto(
    builder: fn(FakeTwo $two, string $s) => new FakeOne(fakeTwo: $two),
    manualParameters: ["s" => "Your value"]
)
```

- `getInstanceOf()`  
  Accepts a `ReflectionNamedType` and attempts to instantiate it.  
  This method is lower-level, and usually, `build()` is sufficient if you're using builder closures.

---

### 🧠 DependencyManager

This is the main class of the library. It handles:

- Registering your dependencies via the `dependenciesMap` property.
- Retrieving a dependency using `getDependency(ClassName::class)`.
- Calling a method with automatic dependency injection via `call($object, $methodName)`.

You can instantiate it manually or extend it for your own manager.  
However, the recommended usage is to call `DependencyManager::get()` to access a shared singleton instance across your application.

---

## ✅ Library Test Coverage

To run the tests, clone the repository and run:

```
php vendor/bin/phpunit
```

### Code Coverage Report

```
Code Coverage Report:
2025-08-15 09:40:48

Summary:
Classes: 75.00% (3/4)
Methods: 61.54% (8/13)
Lines:   91.25% (73/80)

Yahay\PhpDependencyInjector\BuilderDataDto
Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)
Yahay\PhpDependencyInjector\DependencyBuilder
Methods:  16.67% ( 1/ 6)   Lines:  86.79% ( 46/ 53)
Yahay\PhpDependencyInjector\DependencyManager
Methods: 100.00% ( 4/ 4)   Lines: 100.00% ( 22/ 22)
Yahay\PhpDependencyInjector\DependencyMap
Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  4/  4)
```

---

## 🙌 Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.

---

## 📫 Contact

If you have any questions or suggestions, feel free to reach out via [GitHub](https://github.com/yahvya).
