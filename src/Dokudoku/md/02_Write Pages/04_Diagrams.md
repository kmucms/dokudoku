


## Flow Chart

````marcdown
```mermaid
flowchart TD
    A-->B;
    A-->C;
    B-->D;
    C-->D;
```
````

will give you

```mermaid
flowchart TD
    A-->B;
    A-->C;
    B-->D;
    C-->D;
```


## Sequenzdiagramm 

```mermaid
sequenceDiagram
    participant Alice
    participant Bob
    Alice->>Bob: Hallo Bob, wie geht's?
    Bob-->>Alice: Gut, danke!
```

## Gantt-Diagramm 

```mermaid
gantt
title Projektplan
section Planung
Planung         :a1, 2024-01-01, 5d
Entwurf         :after a1, 3d
section Umsetzung
Implementierung :2024-01-10, 7d
Test            :2024-01-17, 3d
```

## Mindmap 

```mermaid
mindmap
  Root
    Idee
      Vorteil
      Nachteil
    Umsetzung
      Schritt 1
      Schritt 2
```

## Notes

There are more examples at: https://mermaid.js.org/intro/.

Basically the diagram js libraries are loaded from cdn.jsdelivr.net.
So you have to be online to see the diagrams.

