Markdown supports various ways to display source code clearly. Here are examples of the most common methods.

## Inline Code

```
An example of `inline code` within a sentence.
```

An example of `inline code` within a sentence.

## Multi-line Code Blocks (no language specified)

````
```
This is a multi-line code block without syntax highlighting.
Multiple lines are possible.
```
````

```
This is a multi-line code block without syntax highlighting.
Multiple lines are possible.
```

## PHP Example

````
```php
<?php
echo "Hello World!";
function add($a, $b) {
    return $a + $b;
}
```
````

```php
<?php
echo "Hello World!";
function add($a, $b) {
    return $a + $b;
}
```

## JavaScript Example
````
```javascript
function greet(name) {
    alert("Hello, " + name + "!");
}
greet("World");
```
````

```javascript
function greet(name) {
    alert("Hello, " + name + "!");
}
greet("World");
```

## Python Example
````
```python
def add(a, b):
    return a + b
print(add(3, 4))
```
````

```python
def add(a, b):
    return a + b
print(add(3, 4))
```

## Bash Example
````
```bash
echo "This is a Bash script!"
ls -l
```
````

```bash
echo "This is a Bash script!"
ls -l
```

## Notes
- For syntax highlighting, you can specify the language after the three backticks (e.g., ```` ```php ````, ```` ```python ````).
- Inline code is written using single backticks (`` ` ``).
- Code blocks are started and ended with three backticks (```` ``` ````).
